<?php
    class SSQTrack
    {
        private $track = NULL;
        private $votes = 0;

        public function __construct($track)
        {
            $this->$track = $track;
        }

        public function addVote()
        {
            $this->votes += 1;
            return $this->votes;
        }

        public function getVotes()
        {
            return $this->votes;
        }
    }
 ?>
