<?php
    
    namespace Core\Contracts;
    
    interface Presentable
    {
        /**
         * @param PresenterInterface $presenter
         *
         * @return mixed
         */
        public function setPresenter(PresenterInterface $presenter);
        
        /**
         * @return mixed
         */
        public function presenter();
    }
