<?php
namespace MyApp;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
echo "Server Running";
class Chat implements MessageComponentInterface {
    protected $clients;
    protected $clientlist;
    protected $idlist;
    protected $classconn;
    protected $dbconn;
    public function __construct() {

        $this->clients = new \SplObjectStorage;
        $this->clientlist=array();
        $this->idlist=array();
        $this->classlist=array();
        $servername = "localhost";
        $uname = "phpmyadmin"; 
        $upwd = "Meiven212!";
        $dbname = "project";
        $this->dbconn = mysqli_connect($servername, $uname, $upwd, $dbname);
        if(!$this->dbconn)
        {
           die("connection failed".mysqli_connect_error());
        }
        $sql="SELECT * FROM courses WHERE state='active'";
        $rows=$this->dbconn->query($sql);
        while($row=$rows->fetch_assoc())
        {
         array_push($this->classlist,$row['name']);
         echo $row['name'];
        }
    }

    public function onOpen(ConnectionInterface $conn) {
        $this->clients->attach($conn);
        
        echo "New connection! ({$conn->resourceId})\n";
    }
    public function setclientid($val1,$val2){
      if(in_array($val1, $this->clientlist))
      {
        $index=array_search($val1, $this->clientlist);
        $this->idlist[$index]=$val2;
        echo "case1";
      }
      else{
        array_push($this->clientlist, $val1);
        array_push($this->idlist, $val2);
        echo "case2";
      }
    }
    public function onMessage(ConnectionInterface $from, $msg) {
        $numRecv = count($this->clients) - 1;
        echo sprintf('Connection %d sending message "%s" to %d other connection%s' . "\n"
            , $from->resourceId, $msg, $numRecv, $numRecv == 1 ? '' : 's');
        $data=json_decode($msg,true);
        if($data['reciever']=="server_checkinitial")
           {
              $this->setclientid($data['sender'],$from->resourceId);
           }
        else{
        foreach ($this->clients as $client) {
              if(in_array($data['reciever'],$this->clientlist))
              {
                //private chat
                $index=array_search($data['reciever'], $this->clientlist);
                if($client->resourceId==$this->idlist[$index])
                {
                   $client->send($msg);
                }
              }
              else
              {
                //group chat
                if(in_array($data['reciever'], $this->classlist))
                {
                   $sql="SELECT * FROM attendance WHERE state='active' AND coursename='".$data['reciever']."'";
                   $rows=$this->dbconn->query($sql);
                   while($row=$rows->fetch_assoc())
                   {
                      if(in_array($row['studentname'],$this->clientlist))
                          {
                            $index=array_search($row['studentname'], $this->clientlist);
                            if($client->resourceId==$this->idlist[$index])
                               {
                                $client->send($msg);
                               }
                          }
                   }
                   $sql="SELECT * FROM courses WHERE name='".$data['reciever']."'";
                   $rows=$this->dbconn->query($sql);
                   while($row=$rows->fetch_assoc())
                   {
                     if(in_array($row['teacher'],$this->clientlist))
                     {
                      $index=array_search($row['teacher'], $this->clientlist);
                      if($client->resourceId==$this->idlist[$index])
                       {
                           $client->send($msg);
                        }
                     }
                   }
                }
              }
        }
      }
    }

    public function onClose(ConnectionInterface $conn) {
        $this->clients->detach($conn);

        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";

        $conn->close();
    }
}
