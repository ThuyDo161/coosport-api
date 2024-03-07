<?php
    include_once('../../config/JwtHandler.php');
    include_once('../../model/account.php');
    class Auth extends JwtHandler{

        protected $db;
        protected $headers;
        protected $token;
        public function __construct($db,$headers) {
            parent::__construct();
            $this->db = $db;
            $this->headers = $headers;
        }

        public function isAuth(){
            if(array_key_exists('Authorization',$this->headers) && !empty(trim($this->headers['Authorization']))):
                $this->token = explode(" ", trim($this->headers['Authorization']));
                if(isset($this->token[1]) && !empty(trim($this->token[1]))):
                    
                    $data = $this->_jwt_decode_data($this->token[1]);
                    if(isset($data['auth']) && isset($data['data']->user_id) && $data['auth']):
                        $user = $this->fetchUser($data['data']->user_id);
                        return $user;

                    else:
                        return null;

                    endif; // End of isset($this->token[1]) && !empty(trim($this->token[1]))
                    
                else:
                    return null;

                endif;// End of isset($this->token[1]) && !empty(trim($this->token[1]))

            else:
                return null;

            endif;
        }

        public function isAuthUpdate($name, $address, $user_tel){
            if(array_key_exists('Authorization',$this->headers) && !empty(trim($this->headers['Authorization']))):
                $this->token = explode(" ", trim($this->headers['Authorization']));
                if(isset($this->token[1]) && !empty(trim($this->token[1]))):
                    
                    $data = $this->_jwt_decode_data($this->token[1]);
                    if(isset($data['auth']) && isset($data['data']->user_id) && $data['auth']):
                        $user = $this->updateUser($data['data']->user_id, $name, $address, $user_tel);
                        return $user;

                    else:
                        return null;

                    endif; // End of isset($this->token[1]) && !empty(trim($this->token[1]))
                    
                else:
                    return null;

                endif;// End of isset($this->token[1]) && !empty(trim($this->token[1]))

            else:
                return null;

            endif;
        }

        public function isAuthChangePass($newPass){
            if(array_key_exists('Authorization',$this->headers) && !empty(trim($this->headers['Authorization']))):
                $this->token = explode(" ", trim($this->headers['Authorization']));
                if(isset($this->token[1]) && !empty(trim($this->token[1]))):
                    
                    $data = $this->_jwt_decode_data($this->token[1]);
                    if(isset($data['auth']) && isset($data['data']->user_id) && $data['auth']):
                        $user = $this->changePassUser($data['data']->user_id, $newPass);
                        return $user;

                    else:
                        return null;

                    endif; // End of isset($this->token[1]) && !empty(trim($this->token[1]))
                    
                else:
                    return null;

                endif;// End of isset($this->token[1]) && !empty(trim($this->token[1]))

            else:
                return null;

            endif;
        }

        protected function fetchUser($user_id){
            $user = new Account($this->db);
            try{
                $user->username = $user_id;
                $result = $user->user();
                if($result->rowCount()):
                    $row = $result->fetch(PDO::FETCH_ASSOC);
                    return [
                        'success' => 1,
                        'status' => 200,
                        'user' => $row
                    ];
                else:
                    return null;
                endif;
            }
            catch(PDOException $e){
                return null;
            }
        }

        protected function changePassUser($user_id, $newPass){
            $user = new Account($this->db);
            try{
                $user->username = $user_id;
                $user->password = $newPass;
                $result = $user->changePass();
                if($result == 200):
                    return [
                        'success' => 1,
                        'code' => 200,
                        'message' => 'Thay đổi mật khẩu thành công! Vui lòng đăng nhập lại!!'
                    ];
                else:
                    return null;
                endif;
            }
            catch(PDOException $e){
                return null;
            }
        }

        protected function updateUser($user_id, $name, $address, $user_tel){
            $user = new Account($this->db);
            try{
                $user->username = $user_id;
                $user->address = $address;
                $user->user_tel = $user_tel;
                $user->name = $name;
                $result = $user->update();
                if($result == 200):
                    return [
                        'success' => 1,
                        'code' => 200,
                        'message' => 'Thay đổi thông tin thành công!'
                    ];
                else:
                    return null;
                endif;
            }
            catch(PDOException $e){
                return null;
            }
        }
    }
?>