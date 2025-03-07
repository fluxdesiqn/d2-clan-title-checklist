<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Title;

class TitleSeeder extends Seeder
{
    public function run()
    {
        $titles = [
            ['activity' => 'dungeon', 'name' => 'sundered-doctrine', 'title_hash' => '2105055614'],
            ['activity' => 'dungeon', 'name' => 'vespers-host', 'title_hash' => '2723381343'],
            ['activity' => 'dungeon', 'name' => 'warlords-ruin', 'title_hash' => '616318467'],
            ['activity' => 'dungeon', 'name' => 'ghosts-of-the-deep', 'title_hash' => '1705744655'],
            ['activity' => 'dungeon', 'name' => 'spire-of-the-watcher', 'title_hash' => '4183969062'],
            ['activity' => 'dungeon', 'name' => 'duality', 'title_hash' => '854126634'],
            ['activity' => 'raid', 'name' => 'last-wish', 'title_hash' => '1486062207'],
            ['activity' => 'raid', 'name' => 'garden-of-salvation', 'title_hash' => '1827854727'],
            ['activity' => 'raid', 'name' => 'deep-stone-crypt', 'title_hash' => '2960810718'],
            ['activity' => 'raid', 'name' => 'vault-of-glass', 'title_hash' => '3734352323'],
            ['activity' => 'raid', 'name' => 'vow-of-the-disciple', 'title_hash' => '2886738008'],
            ['activity' => 'raid', 'name' => 'kings-fall', 'title_hash' => '2613142083'],
            ['activity' => 'raid', 'name' => 'root-of-nightmares', 'title_hash' => '1976056830'],
            ['activity' => 'raid', 'name' => 'crotas-end', 'title_hash' => '238107129'],
            ['activity' => 'raid', 'name' => 'salvations-edge', 'title_hash' => '334829503']
        ];

        foreach ($titles as $title) {
            Title::create($title);
        }
    }
}