<?php
namespace MyApp;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
include '../includes/db.php';
$sql="SELECT * FROM users";
$clientresourceidlist['server_checkinitial']="00";
if(!$conn->query($sql))
  echo "issue";
$rows=$conn->query($sql);
$studentlist=array();
while($row=$rows->fetch_assoc())
{
 array_push($studentlist, $row['name']);
}
class Chat implements MessageComponentInterface {
    protected $clients;

    public function __construct() {
        $this->clients = new \SplObjectStorage;
    }

    public function onOpen(ConnectionInterface $conn) {
        $this->clients->attach($conn);

        echo "New connection! ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        global $clientresourceidlist;
        $numRecv = count($this->clients) - 1;
        echo sprintf('Connection %d sending message "%s" to %d other connection%s' . "\n"
            , $from->resourceId, $msg, $numRecv, $numRecv == 1 ? '' : 's');
        $data=json_decode($msg,true);
        if($data['reciever']=="server_checkinitial")
           {
              $clientresourceidlist[$data['sender']]=$from->resourceId;
           }
        else{
        foreach ($this->clients as $client) {
              $client->send($msg);
              if(isset($clientresourceidlist[$data['reciever']]))
              {
                gettype($client->resourceId);
              }
              else
              {
                echo "offline";
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
