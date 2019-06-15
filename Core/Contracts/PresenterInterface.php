<?php
    
    namespace Core\Contracts;
    
    interface PresenterInterface
    {
        /**
         * Prepare data to present.
         *
         * @param $data
         *
         * @return mixed
         */
        public function present($data);
    }
