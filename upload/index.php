<?php
  if(isset($_POST['model'])){
    $returnModel = $_POST['model'];
    if($returnModel == "imageModel"){
      if(isset($_POST['action'])){
        $returnAction = $_POST['action'];
        if($returnAction == "insert"){

          $poRelated = $_POST['poRelated'];
          $userWhoSent = $_POST['whoIsSending'];

          $teste = $_POST['uris'];

          $newPostUris = str_replace('\"', "", $teste);

          function save_base64_image(
            $base64_image_string, 
            $output_file_without_extension, 
            $path_with_end_slash) {
      
            $splited = explode(',', substr($base64_image_string, 5) , 2);
      
            $mime = $splited[0];
            $data = $splited[1];
        
            $mime_split_without_base64 = explode(';', $mime, 2);
            $mime_split = explode('/', $mime_split_without_base64[0], 2);
      
            if(count($mime_split) == 2){
                $extension = $mime_split[1];
      
                if($extension == 'jpeg'){
                  $extension = 'jpg';
                }
      
                $output_file_with_extension = $output_file_without_extension.'.'.$extension;
            }
      
            if(file_put_contents($path_with_end_slash . $output_file_with_extension, base64_decode($data))){
              return 1;
            }else{
              return 0;
            }
            
          }
              
          define('UPLOAD_DIR', 'data/');

          foreach($newPostUris as $key => $value){
            foreach(json_decode($value) as $newKey => $data){

              $image_base64 = base64_decode($data->uril);

              $response = $data->name;
              $newFileName = UPLOAD_DIR . $data->name;

              file_put_contents($newFileName, $image_base64);

            }
          }

        }else{
          $response = array(
            "success" => false,
            "erro_msg" => "Parametro não reconhecido pela Api.",
            "erro_code" => 4
          );
        }
      }else{
        $response = array(
          "success" => false,
          "erro_msg" => "Não há parametros suficientes.",
          "erro_code" => 3
        );
      }
    }else{
      $response = array(
        "success" => false,
        "erro_msg" => "Parametro não reconhecido pela Api.",
        "erro_code" => 2
      );
    }
  }else{
    // $response = array(
    //   "success" => false,
    //   "erro_msg" => "Não há parametros suficientes.",
    //   "erro_code" => 1
    // );
    $response = $_FILES;
  }

  header('Content-Type: application/json');
  echo json_encode($response);
?>