<?php
    class Data{
        public $data = [
            [
                'id' => 1,
                'name' => 'Иван',
                'email' => 'ivan@mail.ru'
            ],
            [
                'id' => 2,
                'name' => 'Петр',
                'email' => 'petr@mail.ru'
            ],
            [
                'id' => 3,
                'name' => 'Семён',
                'email' => 'semen@mail.ru'
            ],
            [
                'id' => 4,
                'name' => 'Кирилл',
                'email' => 'kirill@mail.ru'
            ],
            [
                'id' => 5,
                'name' => 'Степан',
                'email' => 'stepan@mail.ru'
            ]
        ];
    }
    interface User{
        
        function validation();

    }
    
    class Guest implements User{
        
        public $data;
            
        public $name, $surname, $email, $password, $repassword;
        /**
         * @return void
         */
        public function __construct($name, $email, $password, $repassword, $surname = '')
        {
            $objData = new Data();
            $this->data = $objData->data;
            $this->name = $name;
            $this->surname = $surname;
            $this->email = $email;
            $this->password = $password;
            $this->repassword = $repassword;
        }
        
        public function createUser(){
            $newUser = [
                'id' => count($this->data) + 1,
                'name' => $this->name,
                'email' => $this->email
            ];
            array_push($this->data, $newUser);
            if(file_exists('logs.json')){
                $logs = [
                    date('d.m.Y H:i:s') => 
                    [
                        'email' => "$this->email",
                        'info' => 'User exists'
                    ]
                ];
                $jsonData = json_decode(file_get_contents("logs.json"), true);
                $fp = fopen('logs.json', 'w');
                array_push($jsonData['logs'], $logs);
                fwrite($fp, json_encode($jsonData, JSON_PRETTY_PRINT));
                fclose($fp);
            }else{
                $logs = [
                    date('d.m.Y H:i:s') => 
                    [
                        'email' => "$this->email",
                        'info' => 'User exists'
                    ]
                ];
                $jsonData = json_decode(file_get_contents("logs.json"), true);
                $fp = fopen('logs.json', 'w');
                array_push($jsonData['logs'], $logs);
                fwrite($fp, json_encode($jsonData, JSON_PRETTY_PRINT));
                fclose($fp);
            }
        }

        /**
         * @return string
         */

        public function validation()
        {
            if(!file_exists('logs.json')){
                $l = [
                    'logs' => []
                ];
                $fp = fopen('logs.json', 'w');
                fwrite($fp, json_encode($l, JSON_PRETTY_PRINT));
                fclose($fp);
            }
            try{
                if($this->name == ''){
                    throw new InvalidArgumentException('Введите имя');
                }
                if($this->password == ''){
                    throw new InvalidArgumentException('Пароль не введен');
                }
                if($this->repassword == ''){
                    throw new InvalidArgumentException('Повторный пароль не введен');
                }
                if(!preg_match("/^[a-z]+@[a-z]+\.[a-z]+$/", $this->email)){
                    throw new InvalidArgumentException('Проверьте почту');
                }
                if($this->password != $this->repassword){
                    throw new InvalidArgumentException('Пароли не совпадают');
                }
                for($i = 0; $i < count($this->data); $i++){
                    if($this->data[$i]['email'] == $this->email){
                        throw new InvalidArgumentException('Данный пользователь уже существует');
                    }
                }
            }    
            catch (Exception $e){
                $exept = $e->getMessage();
                if(file_exists('logs.json')){
                    $logs = [
                        date('d.m.Y H:i:s') => 
                        [
                            'email' => "$this->email",
                            'info' => 'User exists'
                        ]
                    ];
                    $jsonData = json_decode(file_get_contents("logs.json"), true);
                    $fp = fopen('logs.json', 'w');
                    array_push($jsonData['logs'], $logs);
                    fwrite($fp, json_encode($jsonData, JSON_PRETTY_PRINT));
                    fclose($fp);
                }else{
                    $logs = [
                        date('d.m.Y H:i:s') => 
                        [
                            'email' => "$this->email",
                            'info' => 'User exists'
                        ]
                    ];
                    $jsonData = json_decode(file_get_contents("logs.json"), true);
                    $fp = fopen('logs.json', 'w');
                    array_push($jsonData['logs'], $logs);
                    fwrite($fp, json_encode($jsonData, JSON_PRETTY_PRINT));
                    fclose($fp);
                }
                
                return $exept;
            }
            $this->createUser();
            return 'true';
        }
    }
    $obj = new Guest($_POST['names'], $_POST['email'], $_POST['password'], $_POST['repassword'], $_POST['surname']);
    echo($obj->validation());
