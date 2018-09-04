<? namespace XT\Option\Model;
class Option 
 {
     public $option_id;
     public $option_name;
     public $option_type; 
     public $option_value;
     public $group_id;
     public $option_des;
     
     
     
     public function __construct() {}
     public function exchangeArray($data)
     {       
      
         $this->option_id     = (!empty($data['option_id'])) ? $data['option_id'] : '';
         $this->option_value = (!empty($data['option_value'])) ? $data['option_value'] : '';
         $this->group_id = (!empty($data['group_id'])) ? $data['group_id'] : ''; 
         $this->option_type = (!empty($data['option_type'])) ? $data['option_type'] : 'string'; 
         $this->option_name = (!empty($data['option_name'])) ? $data['option_name'] : ''; 
         $this->option_des = (!empty($data['option_des'])) ? $data['option_des'] : ''; 
         
             
     }
     public function getArrayCopy()
     {
         return get_object_vars($this);
     }
     public function getInputFilter() 
     {
        $inputFilter = new \Zend\InputFilter\InputFilter();
        
        
        $inputFilter->add([
                         'name'     => 'option_type',                 
                         'filters'  => [
                            [
                                'name'=> 'Whitelist',
                                'options'=> [
                                    'list'=> ['string','integer','numeric','array','boolean','positive_integer','unsigned_integer','unsigned_numeric']
                                ]
                            ],
                         ],
                         'validators' => [
                             ['name'=> 'NotEmpty'],

                         ],
        ]);
    
         
        $inputFilter->add([
                 'name'     => 'option_id',                 
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
                             'min'      => 1,
                             'max'      => 30,
                         ],

                     ],
                 ],
        ]);

        $inputFilter->add([
                 'name'     => 'option_name', 
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
        
        
        return $inputFilter;

     }

    /**
     * @return mixed
     */
    public function getOptionId()
    {
        return $this->option_id;
    }

    /**
     * @param mixed $option_id
     */
    public function setOptionId($option_id)
    {
        $this->option_id = $option_id;
    }

    /**
     * @return mixed
     */
    public function getOptionName()
    {
        return $this->option_name;
    }

    /**
     * @param mixed $option_name
     */
    public function setOptionName($option_name)
    {
        $this->option_name = $option_name;
    }

    /**
     * @return mixed
     */
    public function getOptionType()
    {
        return $this->option_type;
    }

    /**
     * @param mixed $option_type
     */
    public function setOptionType($option_type)
    {
        $this->option_type = $option_type;
    }

    /**
     * @return mixed
     */
    public function getOptionValue()
    {
        return $this->option_value;
    }

    /**
     * @param mixed $option_value
     */
    public function setOptionValue($option_value)
    {
        $this->option_value = $option_value;
    }

    /**
     * @return mixed
     */
    public function getGroupId()
    {
        return $this->group_id;
    }

    /**
     * @param mixed $group_id
     */
    public function setGroupId($group_id)
    {
        $this->group_id = $group_id;
    }

    /**
     * @return mixed
     */
    public function getOptionDes()
    {
        return $this->option_des;
    }

    /**
     * @param mixed $option_des
     */
    public function setOptionDes($option_des)
    {
        $this->option_des = $option_des;
    }


 }