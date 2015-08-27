<?php
namespace NodeAgent;

/**
 * 节点服务器
 * Class Node
 * @package NodeAgent
 */
class Node extends Server
{
    /**
     * @var \swoole_client
     */
    protected $centerSocket;

    protected $centerHost;
    protected $centerPort;

    function init()
    {
        $this->serv->on('WorkerStart', function (\swoole_server $serv, $worker_id)
        {
            //每1分钟向服务器上报
            $serv->tick(60000, [$this, 'onTimer']);
            swoole_event_add($this->centerSocket->sock, [$this, 'onPacket']);
        });
        $this->log(__CLASS__.' is running.');
    }

    function setCenterSocket($ip, $port)
    {
        $this->centerSocket = new \swoole_client(SWOOLE_SOCK_UDP);
        $this->centerSocket->connect($ip, $port);
        $this->centerHost = $ip;
        $this->centerPort = $port;
    }

    function onPacket($sock)
    {
        $data = $this->centerSocket->recv();
        $req = unserialize($data);
        if (empty($req['cmd']))
        {
            $this->log("error packet");
            return;
        }
        if ($req['cmd'] == 'getInfo')
        {
            $this->centerSocket->send(serialize([
                //心跳
                'cmd' => 'putInfo',
                'info' => [
                    //机器HOSTNAME
                    'hostname' => gethostname(),
                    'ipList' => swoole_get_local_ip(),
                    'uname' => php_uname(),
                    'deviceInfo' => '',
                ],
            ]));
        }
    }

    function onTimer($id)
    {
        $this->centerSocket->send(serialize([
            //心跳
            'cmd' => 'heartbeat',
        ]));
    }
}