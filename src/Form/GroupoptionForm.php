<?
 namespace XT\Option\Form;

 use Zend\Form\Form;
 use Zend\I18n\Validator\Alpha;
 use Zend\Validator\Db\NoRecordExists;

 class GroupoptionForm extends \Ichte\Core\Form\Form
 {
     public function __construct($name, $adapter)
     {
         // we want to ignore the name passed
         
         parent::__construct('groupoption');
         
         $this->add([
             'name' => 'id',
             'type' => 'Text',
             'attributes'=> [
                'class'=>'form-control'
             ],

         ]);
         $this->add([
             'name' => 'name',
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


         $inputFilter = new \Zend\InputFilter\InputFilter();

         $inputFilter->add([
             'name'     => 'id',
             'filters'  => [
                 ['name'=>'Ichte\Core\Filter\VietnamLatin'],
                 ['name' => 'Alnum'],
                 ['name' => 'StringTrim',
                     'options'=> [
                         'charlist'=>" ,#,$"
                     ]
                 ],
                 ['name' => 'StringToLower',
                     'options'=> [
                         'encoding'=>"UTF-8"
                     ]
                 ],
             ],
             'validators' => [
                 ['name'    => 'NotEmpty'],
                 [
                     'name'    => 'StringLength',
                     'options' => [
                         'encoding' => 'UTF-8',
                         'min'      => 2,
                         'max'      => 30,
                     ],

                 ],
                 ['name' => Alpha::class, 'options' => ['allowWhiteSpace' => false]],
                 [
                     'name' => NoRecordExists::class,
                     'options' => [
                         'adapter' => $adapter,
                         'table' => 'ahdoption_group',
                         'field' => 'name'
                     ]
                 ]
             ],
         ]);

         $inputFilter->add([
             'name'     => 'name',
             'filters'  => [
                 ['name' => 'StripTags'],
                 ['name' => 'StringTrim'],
             ],
             'validators' => [
                 ['name'    => 'NotEmpty'],
                 [
                     'name'    => 'StringLength',
                     'options' => [
                         'encoding' => 'UTF-8',
                         'min'      => 1,
                         'max'      => 250,
                     ],
                 ],
             ],
         ]);


         $this->setInputFilter($inputFilter) ;


     }
 }