<?php
require('./PDO/taskPDO.php');

class userPDO{
    private const table='*****';
    public function connect(){
        // DB接続設定
        $dsn = 'mysql:dbname=*****;host=*****';
        $user = '*****';
        $password = '*****';
        try{
            $pdo=new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
        }catch(Exception $e){
          echo 'error' .$e->getMesseage;
          die();
        }
        //エラーを表示してくれる。
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
        return $pdo;
    }

    public function getMaxID(){
        // メソッドを呼び出したインスタンスに対してPDO関数を適用
        $pdo = $this->connect();
        // テーブルの名前を取得
        $table_name = self::table;
        // データの習得
        $sql = $pdo->prepare("SELECT id FROM ".$table_name);
        $sql -> execute();
        $data = $sql->fetchAll();
        $max_id=0;  //初期値
        $id=0;      // 初期値
        foreach ($data as $key=>$value){
            $id=$value["id"];
            if ($id > $max_id){
                $max_id=$id;
            }
        }
        return $max_id; //最大のIDをreturn
    }

    public function checkPW($name, $password){
        if($name == '' or $password == ''){
            return '入力がされていません';
        }
        // メソッドを呼び出したインスタンスに対してPDO関数を適用
        $pdo = $this->connect();
        // テーブルの名前を取得
        $table_name = self::table;
        // データの習得
        $sql = $pdo->prepare("SELECT password FROM ".$table_name." WHERE name = :name");
        // 変数にセット
        $sql -> bindParam(':name', $name, PDO::PARAM_STR);
        // SQLの実行
        $sql -> execute();
        $data = $sql->fetchAll();
        foreach ($data as $key => $value){
            if ($password == $value["password"]){
                $_SESSION['user'] = $_POST["name"];
                header('Location: index.php');
            } else{
                header('Location: login.php');
            }
        }

    }

    public function mkUser($name, $password){
        if($name == '' or $password == ''){
            return '入力がされていません';
        }
        // メソッドを呼び出したインスタンスに対してPDO関数を適用
        $pdo = $this->connect();
        // テーブルの名前を取得
        $table_name = self::table;
        // SQL文を用意
        $sql = $pdo -> prepare("INSERT INTO ".$table_name." (name, password) VALUES (:name, :password)");
        // 各変数にセット
        $sql -> bindParam(':name', $name, PDO::PARAM_STR);
        $sql -> bindParam(':password', $password, PDO::PARAM_STR);
        // SQLの実行
        $sql -> execute();
    }

    public function delUser($name){
        // userの情報を消す
        $pdo = $this->connect();
        // テーブルの名前を取得
        $table_name = self::table;
        $sql = $pdo->prepare("DELETE FROM ".$table_name." WHERE name = :name");
        $sql -> bindParam(':name', $name, PDO::PARAM_STR);
        $sql -> execute();
    }

    public function exit($name, $password){
        if($name == '' or $password == ''){
            return '入力がされていません';
        }
        // メソッドを呼び出したインスタンスに対してPDO関数を適用
        $pdo = $this->connect();
        // テーブルの名前を取得
        $table_name = self::table;
        // データの習得
        $sql = $pdo->prepare("SELECT password FROM ".$table_name." WHERE name = :name");
        // 変数にセット
        $sql -> bindParam(':name', $name, PDO::PARAM_STR);
        // SQLの実行
        $sql -> execute();
        $data = $sql->fetchAll();
        var_dump($data);
        if ($data == array()){
            header('Location: exit.php');
        }
        if ($password == $data[0]["password"]){
            // userの情報を消す
            $this->delUser($name);
            // taskの情報を消す
            $taskpdo = new taskPDO();
            $taskpdo->delALLData($name);
            header('Location: login.php');
        } else{
            header('Location: exit.php');
        }
    }
}

?>