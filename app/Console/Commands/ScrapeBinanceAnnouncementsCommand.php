<?php

namespace App\Console\Commands;

use App\Http\Webhooks\Telegram\Bot;
use App\Models\BinanceListing;
use App\Models\TelegramChat;
use App\Notifications\NewBinanceListing;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Symfony\Component\Panther\Client;
use Symfony\Component\Panther\DomCrawler\Crawler;

class ScrapeBinanceAnnouncementsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scrape:binance';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scrape Binance announcements for new coin listings.';

    /**
     * @var string
     */
    protected string $announcementsUrl = 'https://www.binance.com/en/support/announcement/c-48/';

    /**
     * @var Crawler|null
     */
    protected ?Crawler $crawler = null;

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        libxml_use_internal_errors(true);

        $client = Client::createChromeClient();
        $response = $client->get($this->announcementsUrl);

        try {
            while (true) {
                $listings = collect();

                $this->crawler = ! is_null($this->crawler)
                    ? $client->reload()
                    : $response->getCrawler();

                $this->crawler
                    ->filter('body div#__APP main section')
                    ->children()
                    ->last()
                    ->children()
                    ->each(static function (Crawler $node) use ($listings) {
                        $anchor = $node->children()->first();
                        $url = $anchor->attr('href');
                        $announcementId = Str::of($url)->after('en/support/announcement/');
                        $meta = $anchor->children()->first();
                        $date = $meta->filter('h6')->text();
                        $title = Str::of($meta->text())->remove($date);

                        $listings->push([
                            'announcement_id' => (string) $announcementId,
                            'title' => (string) $title,
                            'date' => $date,
                            'url' => 'https://www.binance.com' . $url,
                        ]);
                    });

                $storedAnnouncementIds = BinanceListing::pluck('announcement_id')->toArray();
                $subscribers = TelegramChat::where('channel', 'binance')->get();

                $listings
                    ->filter(fn ($listing) => Carbon::parse($listing['date'])->isAfter(Carbon::parse(Bot::LAUNCH_DATE)))
                    ->filter(fn ($listing) => !in_array($listing['announcement_id'], $storedAnnouncementIds, true))
                    ->each(fn ($listing) => $this->storeListingAndNotify($listing, $subscribers));

                sleep(random_int(10, 60));
            }
        } catch (Exception $e) {
            $this->error($e->getMessage());

            return 1;
        }
    }

    /**
     * @param array $data
     * @param Collection $subscribers
     */
    protected function storeListingAndNotify(array $data, Collection $subscribers): void
    {
        $this->info('Storing new announcement: ' . $data['title']);

        $listing = BinanceListing::create([
            'announcement_id' => $data['announcement_id'],
            'url' => $data['url'],
            'title' => $data['title'],
        ]);

        $subscribers->each(fn (TelegramChat $subscriber) => $subscriber->notify(new NewBinanceListing($listing)));
    }
}
