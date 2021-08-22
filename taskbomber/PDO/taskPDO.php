<?php

class taskPDO{
    private const table='***';
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
        // メソッドを呼び出したインスタンスに対してPOD関数を適用
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

    public function getAllData($user){
        // メソッドを呼び出したインスタンスに対してPDO関数を適用
        $pdo = $this->connect();
        // テーブルの名前を取得
        $table_name = self::table;
        // データの習得
        $sql = $pdo->prepare("SELECT id, name, deadline, level FROM ".$table_name." WHERE user = :user ORDER BY deadline");
        $sql -> bindParam(':user', $user, PDO::PARAM_STR);
        $sql -> execute();
        $data = $sql->fetchAll();
        return $data;
    }
    public function getAllDataLevel($user){
        // メソッドを呼び出したインスタンスに対してPDO関数を適用
        $pdo = $this->connect();
        // テーブルの名前を取得
        $table_name = self::table;
        // データの習得
        $sql = $pdo->prepare("SELECT id, name, deadline, level FROM ".$table_name." WHERE user = :user ORDER BY level DESC, deadline");
        $sql -> bindParam(':user', $user, PDO::PARAM_STR);
        $sql -> execute();
        $data = $sql->fetchAll();
        return $data;
    }

    public function mkData($name, $deadline, $level, $user){
        // メソッドを呼び出したインスタンスに対してPOD関数を適用
        $pdo = $this->connect();
        // テーブルの名前を取得
        $table_name = self::table;
        // 最大のIDを取得しそのIDより1大きい値を新しいIDの値にする
        $id = $this->getMaxID();
        $id++;
        // SQL文を用意
        $sql = $pdo -> prepare("INSERT INTO ".$table_name." (id, name, deadline, level, user) VALUES (:id, :name, :deadline, :level, :user)");
        // 各変数にセット
        $sql -> bindParam(':id', $id, PDO::PARAM_INT);
        $sql -> bindParam(':name', $name, PDO::PARAM_STR);
        $sql -> bindParam(':deadline', $deadline);
        $sql -> bindParam(':level', $level, PDO::PARAM_INT);
        $sql -> bindParam(':user', $user);
        // SQLの実行
        $sql -> execute();
    }

    public function delData($id){
        // メソッドを呼び出したインスタンスに対してPOD関数を適用
        $pdo = $this->connect();
        // テーブルの名前を取得
        $table_name = self::table;
        // データの習得
        $sql = $pdo->prepare("DELETE FROM ".$table_name." WHERE id = :id");
        $sql -> bindParam(':id', $id, PDO::PARAM_INT);
        // $sql -> bindParam(':table', $table_name, PDO::PARAM_STR);
        $sql -> execute();
    }
    public function delALLData($user){
        // メソッドを呼び出したインスタンスに対してPOD関数を適用
        $pdo = $this->connect();
        // テーブルの名前を取得
        $table_name = self::table;
        // データの習得
        $sql = $pdo->prepare("DELETE FROM ".$table_name." WHERE user = :user");
        $sql -> bindParam(':user', $user, PDO::PARAM_STR);
        // $sql -> bindParam(':table', $table_name, PDO::PARAM_STR);
        $sql -> execute();
    }
}

?>