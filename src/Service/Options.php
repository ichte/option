<?
namespace XT\Option\Service;


 use XT\Option\Model\Option;
 use Zend\Db\TableGateway\TableGateway;

 class Options extends TableGateway
 {
   

     public function saveOptionValue($id,$val)
     {
            $boolfillter    = new \Zend\Filter\Boolean();
            $filter_num     = new \Zend\Filter\ToInt();
            $trim           = new \Zend\Filter\StringTrim();

            $r=$this->find($id);
            if ($r == null)
                return;
            $val = $trim->filter($val);
            if ($r->option_type=='bolean') $val = $boolfillter->filter($val);
            if ($r->option_type=='integer') $val = $filter_num->filter($val);

            $this->update(['option_value'=>$val], ['option_id'=>$id]);



     }

     /**
      * @param $id_option
      * @return array|\ArrayObject|Option
      */
     public function find($id_option) {
         return $this->select(['option_id' => $id_option])->current();
     }
   
 }