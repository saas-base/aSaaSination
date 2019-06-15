<?php
    
    namespace Core\Contracts;
    
    interface RepositoryTransformable
    {
        /**
         * @return array
         */
        public function transform();
    }
