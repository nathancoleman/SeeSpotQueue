<?php
    //require_once('SSQTrack.php');

    class SSQQueue
    {
        private $queue = array();
        private $currentIndex = 0;
        private $currentTrack = NULL;

        public function addTrack($track)
        {
            $this->queue[] = $track;

            if (count($this->queue) == 1)
            {
                $this->currentTrack = $this->queue[0];
            }

            return count($this->queue);
        }

        public function currentTrack()
        {
            return $this->currentTrack;
        }

        public function nextTrack()
        {
            $this->currentIndex += 1;
            $this->currentTrack = $this->queue[$this->currentIndex];
            return $this->currentTrack;
        }

        public function voteTrack($index)
        {
            //$this->queue[$index].addVote();
            //return $this->queue[$index].getVotes();
        }
    }

 ?>
