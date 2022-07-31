<?php

namespace App\Nova\Actions;

use App\Models\Building;
use App\Models\Dump;
use App\Models\Payload;
use App\Models\RoomInformation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Line;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;

class GetData extends Action
{
    use InteractsWithQueue, Queueable;

    /**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        $fileName = $models[0]->attachment;

        $contents = Storage::disk('public')->get($fileName);

        $a = 'app/public/' . $fileName;
        $file_path = storage_path($a);
        $contents = file($file_path);

        foreach ($contents as $line) {
            $explode_string = explode(';', $line); // split data in one line by ';'
            $area_json = json_decode($explode_string[2], true); // decode from json from to array
            $area_key = array_keys($area_json['areas']); // get all key (each room)

            // loop through each element of the area array (each room)
            for ($i = 0; $i < count($area_json['areas']); $i++) {

                $room = Building::where('room_name', '=', $area_key[$i])->first();
                if ($room === null) {

                    $model = new Building();
                    $model->room_name = $area_key[$i];

                    // save each room to Building table
                    if ($model->save()) {
                        Log::alert('Saved room to Building table! ' . $area_key[$i]);
                        $room_id = $model->id;

                        // processing room data to get its information
                        $room_info_model = new RoomInformation();
                        if (strlen($area_key[$i]) == 4) {
                            if ($area_key[$i][0] == '0') {
                                $room_info_model->floor = "Ground Floor";
                            }
                            if ($area_key[$i][0] == '1') {
                                $room_info_model->floor = "First Floor";
                            }
                            if ($area_key[$i][0] == '2') {
                                $room_info_model->floor = "Second Floor";
                            }
                            if ($area_key[$i][0] == '3') {
                                $room_info_model->floor = "Third Floor";
                            }
                            if ($area_key[$i][0] == '4') {
                                $room_info_model->floor = "Fourth Floor";
                            }
                            if ($area_key[$i][0] == '5') {
                                $room_info_model->floor = "Fifth Floor";
                            }
                            if ($area_key[$i][0] == '6') {
                                $room_info_model->floor = "Sixth Floor";
                            }
    
                            $room_number = $area_key[$i][2] . $area_key[$i][3];
                            $room_info_model->room = $room_number;
                        } else {
                            $room_number = $area_key[$i][4] . $area_key[$i][5];
                            $room_info_model->room = $room_number;
                        }
    
                        if ($area_key[$i][0] == '0') {
                            $room_info_model->floor = "Ground Floor";
                        }
                        if ($area_key[$i][0] == '1') {
                            $room_info_model->floor = "First Floor";
                        }
                        if ($area_key[$i][0] == '2') {
                            $room_info_model->floor = "Second Floor";
                        }
                        if ($area_key[$i][0] == '3') {
                            $room_info_model->floor = "Third Floor";
                        }
                        if ($area_key[$i][0] == '4') {
                            $room_info_model->floor = "Fourth Floor";
                        }
                        if ($area_key[$i][0] == '5') {
                            $room_info_model->floor = "Fifth Floor";
                        }
                        if ($area_key[$i][0] == '6') {
                            $room_info_model->floor = "Sixth Floor";
                        }
    
                        if ($area_key[$i][1] == 'A') {
                            $room_info_model->section = "Section A";
                        }
                        if ($area_key[$i][1] == 'B') {
                            $room_info_model->section = "Section B";
                        }
                        if ($area_key[$i][1] == 'C') {
                            $room_info_model->section = "Section C";
                        }
                        if ($area_key[$i][1] == 'D') {
                            $room_info_model->section = "Section D";
                        }
    
                        if ($area_key[$i][3] == '2') {
                            $room_info_model->room_type = "Corridor Room";
                        } else {
                            $room_info_model->room_type = "Normal Room";
                        }

                        $room_info_model->room_id = $room_id;
    
                        if ($room_info_model->save()) {
                            Log::info("Save room information!!!");

                        } else {
                            Log::info("Failed to save room information to database!!!");
                            return Action::danger('Failed to save into table!');
                        }

                    } else {
                        Log::alert("Failed to save!");
                    }
                } else {
                    Log::info("Data exists!!!");
                    return Action::danger('Failed to save into table! Data exists!');
                }
            }

            break;
        }


        // save payload data into database
        foreach ($contents as $line) {
            $explode_string = explode(';', $line);
            $model_payload = new Payload();
            $model_payload->timestamp = $explode_string[0]; // get timestamp
            $model_payload->json = $explode_string[2]; // get payload

            if($model_payload->save()){
                Log::info("Save payload into tables successfully!");
            }else{
                Log::info("Failed to save into table!");
                return Action::danger('Failed to save into table!');
            }
        }

        return Action::message('Saved successfully!');
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        return [

        ];
    }
}
