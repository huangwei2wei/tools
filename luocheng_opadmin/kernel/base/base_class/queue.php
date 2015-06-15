<?php
namespace kernel\base\base_class;
/**
 * 队列
 */
class queue
{
	private $maxQSize = 0; // 队列最大长度  
    private $front = 0; // 队头指针  
    private $rear = 0;  // 队尾指针  
      
    private $blockSize = 256;  // 块的大小(byte)  
    private $memSize = 25600;  // 最大共享内存(byte)  
    private $shmId = 0;  
      
    private $filePtr = './shmq.ptr';
      
    private $semId = 0;
    
    public function __construct()  
    {         
        $shmkey = ftok(__FILE__, 't');  
          
        $this->shmId = shmop_open($shmkey, "c", 0644, $this->memSize );  
        $this->maxQSize = $this->memSize / $this->blockSize;  
          
         // 申請一个信号量  
        $this->semId = sem_get($shmkey, 1);  
        sem_acquire($this->semId); // 申请进入临界区          
          
        $this->init();  
    }  
      
    private function init()  
    {  
        if ( file_exists($this->filePtr) ){  
            $contents = file_get_contents($this->filePtr);  
            $data = explode( '|', $contents );  
            if ( isset($data[0]) && isset($data[1])){  
                $this->front = (int)$data[0];  
                $this->rear  = (int)$data[1];  
            }  
        }  
    }  
      
    public function getLength()  
    {  
        return (($this->rear - $this->front + $this->memSize) % ($this->memSize) )/$this->blockSize;  
    }  
      
    public function enQueue( $value )  
    {  
        if ( $this->ptrInc($this->rear) == $this->front ){ // 队满  
            return false;  
        }  
          
        $data = $this->encode($value);  
        shmop_write($this->shmId, $data, $this->rear);  
        $this->rear = $this->ptrInc($this->rear);  
        return true;  
    }  
          
    public function deQueue()  
    {  
        if ( $this->front == $this->rear ){ // 队空  
            return false;  
        }  
        $value = shmop_read($this->shmId, $this->front, $this->blockSize-1);  
        $this->front = $this->ptrInc($this->front);  
        return $this->decode($value);  
    }  
      
    private function ptrInc( $ptr )  
    {  
        return ($ptr + $this->blockSize) % ($this->memSize);  
    }  
      
    private function encode( $value )  
    {  
        $data = serialize($value) . "__eof";  
        if ( strlen($data) > $this->blockSize -1 ){  
            throw new Exception(strlen($data)." is overload block size!");  
        }  
        return $data;  
    }  
      
    private function decode( $value )  
    {  
        $data = explode("__eof", $value);  
        return unserialize($data[0]);         
    }  
      
    public function __destruct()  
    {  
        $data = $this->front . '|' . $this->rear;  
        file_put_contents($this->filePtr, $data);  
          
        sem_release($this->semId); // 出临界区, 释放信号量  
    }
}

/* 使用说明
// 进队操作  
$shmq = new SHMQueue();  
$data = 'test data';  
$shmq->enQueue($data);  
unset($shmq);  
// 出队操作  
$shmq = new SHMQueue();  
$data = $shmq->deQueue();  
unset($shmq); 
*/
?>