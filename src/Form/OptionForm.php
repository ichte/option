<?
 namespace XT\Option\Form;

 use Zend\Form\Form;

 class OptionForm extends \Ichte\Core\Form\Form
 {
     public function __construct($name = null)
     {

         parent::__construct('optionform');
        
         $this->add([
             'name' => 'option_id',
             'type' => 'Text',
             'attributes'=> [
                'class'=>'form-control'
             ],

         ]);
         $this->add([
             'name' => 'option_name',
             'type' => 'Text',
             'attributes'=> [
                'class'=>'form-control'
             ],
         ]);
         
         $this->add([
             'name' => 'option_type',
             'type' => 'Zend\Form\Element\Select',
             'attributes'=> [
                'class'=>'form-control'
             ],
             'options' => [
                      
                     'empty_option' => 'Phải chọn kiểu dữ liệu Option',
                     'value_options' => [
                            'string' =>  'Dữ liệu dạng chuỗi',
                            'integer' => 'Số nguyên',
                            'numeric' => 'Số numeric',
                            'array' =>'Mảng',
                            'boolean' => 'True/False',
                            'positive_integer' => 'Nguyên âm',
                            'unsigned_integer' => 'Số nguyên dương',
                            'unsigned_numeric' => 'Số dương'
                     ],
             ]
         ]);
         
        
          $this->add([
             'name' => 'option_des',
             'type' => 'Text',
             'attributes'=> [
                'class'=>'form-control'
             ],
          ]);
         
          $this->add([
             'name' => 'group_id',
             'type' => 'Text',
             'attributes'=> [
                'class'=>'form-control'
             ],
          ]);
         
         $this->add([
             'name' => 'submit',
             'type' => 'Submit',
             'attributes' => [
                 'value' => 'Save',
                 'id' => 'submitbutton',
                 'class'=>'btn btn-secondary'
             ],
         ]);
         
     }
 }