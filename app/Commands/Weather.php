<?php

namespace App\Commands;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

class Weather extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'weather
                            {city : The name of the city (required)}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Weather Status for some city';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $city = $this->argument('city');
        $response = $this->getWeather($city);
        $this->info("{$response->name}-{$response->sys->country}: {$response->main->temp}°C");
        $this->line("Max:{$response->main->temp_max}°C      Min:{$response->main->temp_min}°C");
    }

    /**
     * Define the command's schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    public function schedule(Schedule $schedule): void
    {
        // $schedule->command(static::class)->everyMinute();
    }
    /**
     * Get data from api
     *
     * @return std::class
     */
    protected function getWeather(string $city)
    {

        //the problem is the request not sending the q=city  !!
        $baseUrl = "https://community-open-weather-map.p.rapidapi.com/weather?q={$city}&units=metric";
        $header =  [
            "X-RapidAPI-Key" => env('API_KEY'),
            "x-rapidapi-host" => "community-open-weather-map.p.rapidapi.com",
            "Accept" => "application/json"

        ];

        $client = new Client();
        $response = $client->request('GET', $baseUrl, [
            'headers' => $header
        ]);


        if ($response->getStatusCode() == 200) {
            $result = json_decode($response->getBody());
            return $result;
        } else {
            $this->error('There is an error in api. Please check it.');
        }
    }
}
