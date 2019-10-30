<?php

namespace App\Commands;

use GuzzleHttp\Client;
use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

class LiveScore extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'score:live';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'show football live score';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $result = $this->getLiveScore();
        $leagueId = 0;
        $counter = 0;
        $this->putDataToJsonFile($result);
        foreach ($result->api->fixtures as $match) {
            $counter += 1;
            $league = $this->getLeague($match->league_id);
            // @ToDo i sold the problem but need the modify codes!!
            if (is_null($league))
                $this->error('Unknow League!');

            if (!is_null($league) && $league && $leagueId != $league->league_id) {

                $this->error("{$league->name} - {$league->country}");
                $leagueId = $league->league_id;
            }

            $this->info("   {{$counter}}{$match->elapsed}: {$match->homeTeam->team_name} {$match->goalsHomeTeam} - {$match->goalsAwayTeam} {$match->awayTeam->team_name}");
        }
    }

    /**
     * Define the command's schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    public function schedule(Schedule $schedule): void
    {
        $schedule->command(static::class)->everyMinute();
    }

    /**
     * get live score from api
     *
     * @return void
     */
    protected function getLiveScore()
    {
        $baseUrl = 'https://api-football-v1.p.rapidapi.com/v2/fixtures/live/';
        $header =  [
            "X-RapidAPI-Key" => env('API_KEY'),
            "x-rapidapi-host" => "api-football-v1.p.rapidapi.com",
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

    protected function getLeague($id = 0)
    {
        $data = json_decode(file_get_contents(public_path('leagues.json')));
        $findLeague = [];
        foreach ($data->api->leagues as $league) {
            if ($league->league_id == $id) {
                $findLeague =  $league;
                break;
            }
        }
        return isset($findLeague) ? $findLeague : null;
    }


    protected function putDataToJsonFile($data)
    {
        $json = json_encode($data, JSON_NUMERIC_CHECK);
        $job = file_put_contents(public_path('test.json'), $json);
        if ($job) {
            return true;
        } else {
            return false;
        }
    }
}
