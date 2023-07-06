<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AgeGroup;
use App\Models\Skill;
use App\Models\Milestone;
use App\Models\Activity;
use App\Models\Level;
use App\Models\LevelOption;

class SkillActivtiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         $faker = \Faker\Factory::create();
               for ($i = 30; $i <= 60; $i++) {
                    $milestone = new Milestone;
                    $milestone->skill_id = 40;
                    $milestone->age_group_id = $i;
                    $milestone->name =  $faker->sentence();
                    $milestone->description =  $faker->paragraph($nbSentences = 3, $variableNbSentences = true);
                    $milestone->description2 =  $faker->paragraph($nbSentences = 3, $variableNbSentences = true);
                    $milestone->s_no =  'M'.rand(1,10);
                    $milestone->save();
                    $j=0;
                     for ($k = 1; $k <= mt_rand(1,30); $k++) {
                        $j++;
                             $activity = new Activity;
                            $activity->milestone_id  = $milestone->id;
                            $activity->name  = 'GM-'.$milestone->s_no.'-'.'A'.$j;
                            $activity->description  = $faker->sentence();
                            $activity->day  = $k;
                             $activity->s_no =  'A'.$j;
                             $activity->save();
                                  $Level = new level;
                                  $Level->activity_id  = $activity->id;
                                  $Level->name  = $activity->name.'-L1';
                                  $Level->description =  $faker->sentence();
                                  $Level->s_no =  'L1';
                                  $Level->save();

                                  $level_option_1 = new LevelOption;
                                  $level_option_1->level_id = $Level->id;
                                  $level_option_1->option_text = 'Yes';
                                  $level_option_1->points = '2';
                                  $level_option_1->save();
                                    $level_option_2 = new LevelOption;
                                  $level_option_2->level_id = $Level->id;
                                  $level_option_2->option_text = 'Sometimes';
                                  $level_option_2->points = '1';
                                  $level_option_2->save();
                                    $level_option_3 = new LevelOption;
                                  $level_option_3->level_id = $Level->id;
                                  $level_option_3->option_text = 'N0';
                                  $level_option_3->points = '0';
                                  $level_option_3->save();
                                



                                 $Level = new level;
                                 $Level->activity_id  = $activity->id;
                                 $Level->name  = $activity->name.'-L2';
                                  $Level->description =  $faker->sentence();
                                  $Level->s_no =  'L2';
                                  $Level->save();
                                     $level_option_1 = new LevelOption;
                                  $level_option_1->level_id = $Level->id;
                                  $level_option_1->option_text = 'Yes';
                                  $level_option_1->points = '2';
                                  $level_option_1->save();
                                    $level_option_2 = new LevelOption;
                                  $level_option_2->level_id = $Level->id;
                                  $level_option_2->option_text = 'Sometimes';
                                  $level_option_2->points = '1';
                                  $level_option_2->save();
                                    $level_option_3 = new LevelOption;
                                  $level_option_3->level_id = $Level->id;
                                  $level_option_3->option_text = 'N0';
                                  $level_option_3->points = '0';
                                  $level_option_3->save();
                     }
               }
    }
}
