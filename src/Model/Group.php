<?
namespace XT\Option\Model;

class Group
 {
    public $id;

    public $name;

    public function exchangeArray($data)
    {
     $this->id     = (!empty($data['id']))   ? $data['id'] : null;
     $this->name =   (!empty($data['name'])) ? $data['name'] : null;
    }

    public function getArrayCopy()
    {
     return get_object_vars($this);
    }

    public function getId()
    {
    return $this->id;
    }

    public function setId($id)
    {
    $this->id = $id;
    }

    public function getName()
    {
    return $this->name;
    }

    public function setName($name)
    {
    $this->name = $name;
    }


 }