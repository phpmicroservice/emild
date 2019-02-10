<?php
//include './logo.php';
echo "开始主程序! \n";
define("APP_SERVICE_NAME", "email");# 设置服务名字
define('ROOT_DIR', dirname(__DIR__));
require ROOT_DIR . '/vendor/autoload.php';
# 进行一些项目配置
define('APP_SECRET_KEY', get_env("APP_SECRET_KEY"));


$re9 = env_exist([
    'MYSQL_HOST', 'MYSQL_PORT', 'MYSQL_DBNAME', 'MYSQL_PASSWORD', 'MYSQL_USERNAME', 'aliyun_dm_accessKey', 'aliyun_dm_accessSecret', 'aliyun_dm_accountName', 'aliyun_dm_tagName']);
if (is_string($re9)) {
    exit('defined :' . $re9);
}


//注册自动加载
$loader = new \Phalcon\Loader();
$loader->registerNamespaces(
    [
        'apps' => ROOT_DIR . '/apps/',
        'tool' => ROOT_DIR . '/tool/',
    ]
);
$loader->register();

$server = new \pms\Server('0.0.0.0', 9502, SWOOLE_BASE, SWOOLE_SOCK_TCP, [
    'daemonize' => false,
    'reload_async' => false,
    'reactor_num_mulriple' => 1,
    'worker_num_mulriple' => 1,
    'task_worker_num_mulriple' => 1,
    'open_eof_split' => true, //打开EOF检测
    'package_eof' => PACKAGE_EOF, //设置EOF
    'task_worker_num_mulriple'=>0
]);
$guidance = new \app\Guidance();
$server->onBind('onWorkerStart', $guidance);
$server->onBind('beforeStart', $guidance);
$server->start();
