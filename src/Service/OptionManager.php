<?php

namespace XT\Option\Service;


use XT\Option\Model\Option;
use Zend\Config\Config;

class OptionManager extends Config
{
    /**
     * @var Groups
     */
    protected $groupTable;
    /**
     * @var Options
     */
    protected $optionTable;

    /**
     * @param mixed $groupTable
     */
    public function setGroupTable($groupTable)
    {
        $this->groupTable = $groupTable;
    }

    /**
     * @param mixed $optionTable
     */
    public function setOptionTable($optionTable)
    {
        $this->optionTable = $optionTable;
    }



    public function buildconfig()
    {

        $grouptb =   $this->groupTable;
        $optiontb =  $this->optionTable;
        $groups = $grouptb->select();
        $arrayconfig = [];
        /**
         * @param $op Option
         * @return bool|int
         */
        $getval = function($op)
        {
            switch ($op->getOptionType()){
                case 'integer':
                    return (int)$op->getOptionValue();
                    break;
                case 'boolean':
                    return (bool)$op->getOptionValue();
                    break;
                default: return $op->getOptionValue(); break;
            }
        };

        foreach ($groups as $gr)
        {
            $ar = [];
            $ops = $optiontb->select(['group_id'=>$gr->id]);
            foreach ($ops as $op)
            {
                /**
                 * @var $op Option
                 */

                $ar[$op->getOptionId()] = $getval($op);
            }


            $arrayconfig[$gr->id] = $ar;
        }



        $arrayconfig = ['AHD'=>$arrayconfig];



        $config = new \Zend\Config\Config($arrayconfig, true);
        $config->AHD->common->pathtemplatedefault = "__DIR__.\"/../module/Application/xttemplate\"";
        $config->AHD->common->rootpath = "__DIR__.\"/DXT/\"";


        if (!file_exists('config/ahd.config.backup'))
        mkdir('config/ahd.config.backup');
        $backupfile = 'config/ahd.config.backup/'.time().'.bak';

        if (file_exists('config/ahd.config.php'))
            rename('config/ahd.config.php', $backupfile);

        $writer = new \Zend\Config\Writer\PhpArray();

        $writer->toFile("config/ahd.config.php",$config);

        $content = file_get_contents('config/ahd.config.php');
        $content = str_replace('\'__DIR__."/../module/Application/xttemplate"\'','__DIR__."/../module/Application/xttemplate"' ,$content);
        $content = str_replace('\'__DIR__."/DXT/"\'','realpath(__DIR__.\'/../\')' ,$content);

        $byte = file_put_contents('config/ahd.config.php',$content);
        if ($byte === false)  throw new \Exception("Can not save: config/ahd.config.php");

        flush(); 

        return $arrayconfig;

    }

    public function listoptions()
    {
        $grouptb =   $this->groupTable;
        $optiontb =  $this->optionTable;
        $groups = $grouptb->select();
        $arrayconfig = [];
        /**
         * @param $op Option
         * @return bool|int
         */

        foreach ($groups as $gr)
        {

            $ar = [];
            $ops = $optiontb->select(['group_id'=>$gr->id]);
            foreach ($ops as $op)
            {
                $ar[] = $op;
            }

            $arrayconfig[] = [
                'group' => $gr,
                'item' => $ar
            ];
        }

        return $arrayconfig;

    }
}