<?php  
namespace XT\Option\Controller;

use Common;
use XT\Option\Form\GroupoptionForm;
use XT\Option\Form\OptionForm;
use XT\Option\Model\Group;
use XT\Option\Model\Option;
use XT\Option\Service\Factory\GroupsFactory;
use XT\Option\Service\Groups;
use XT\Option\Service\OptionManager;
use XT\Option\Service\Options;
use Zend\Db\TableGateway\TableGateway;
use Zend\File\ClassFileLocator;
use Zend\Validator\Db\NoRecordExists;
use Zend\Validator\Db\RecordExists;
use Zend\View\Model\ViewModel;

class OptionController extends \Ichte\Core\Controller\XtController
{
    protected $directory = "config";
    protected $directorybackup = "config/ahd.config.backup";
    protected $filenameconfig = "ahd.config.php";


    var $options_default = [
        //contactsite
        ['descriptioncontact','Mô tả Description','Mô tả cho trang contact','Liện hệ với Bếp Xinh để tư vấn về kiến trúc và nội thất, nhận báo giá thiết bị nhà bếp','string','contactsite'],
        ['foldermail','Foder lưu trữ email','Foder lưu trữ email - cho trường hợp chọn file','data\\mail','string','contactsite'],
        ['mailtransport','Mail Transport','Mail Transport:sendmail hoặc smtp hoặc file','sendmail','string','contactsite'],
        ['titlecontact','Tiêu đề Contact','Tiêu đề hội thoại Contact','Liên hệ - gửi yêu cầu tư vấn - contactLiên hệ tới Bếp Xinh','string','contactsite'],
        ['titleformcontact','Tiêu đề Form','Tiêu đề titleformcontact - NHẬP CÁC THÔNG TIN VÀ YÊU CẦU CỦA BẠN','NHẬP CÁC THÔNG TIN VÀ YÊU CẦU CỦA BẠN','string','contactsite'],
        ['recaptcha','Chống spam Recaptcha','Bật tắt chế độ chống spam',false,'boolean','contactsite'],
        ['emailcontact','Email','Địa chỉ Email nhận liên hệ','bepxinh.vn@gmail.com','string','contactsite'],



        //product
        ['catehome','Cate Home','Category được hiện thị trang bắt đâu','125,395,73,147,59,200,309,343,344,345','string','product'],
        ['numberitem','Số SP/Trang','Số lượng sản phẩm một trang','24','integer','product'],
        ['allowfullhtml','Full HTML','Cho phép soạn thảo Full HTML',false,'boolean','product'],
        ['onshowroomproduct','Thêm dòng showroom','Thêm dòng showroom ở sản phẩm chi tiết',false,'boolean','product'],

        //tintuc
        ['urlitem','URL tin tức','Cấu tạo URL ví dụ: bepxinh: {name}; nnt: {name}-{id}; ahdsoft: {name}.html','{name}-{id}','string','tintuc'],
        ['numberrow','Số tin','Số lượng tin hiện thị mỗi trang backend',20,'integer','tintuc'],
        ['sendnavmenu','Nav menu','Gửi event render nav menu',true,'boolean','tintuc'],
        ['viewitemnextpre','Next/Pre','Hiện thị điều hướng bài trước sau',true,'boolean','tintuc'],
        ['numbernewnews','Số tin mới','Số lượng tin mới trong bài viết (2)',2,'integer','tintuc'],
        ['numberothernews','Số tin khác','Số lượng tin khác trong bài viết (7)',7,'integer','tintuc'],
        ['onrelatenews','Box tin liên quan','Hiện thị box tin quan',true,'boolean','tintuc'],
        ['fetitemhot','ItemHot','Fetch Hot at category',false,'boolean','tintuc'],
        ['metallnews','Meta Category All','title:des:keyword','Tư vấn thiết kế nội thất và tủ bếp:Tư vấn thực hiện các hồ sơ thiết kế nội thất các hạng mục nội thất nhà như nội thất phòng khách, nội thất bếp, tủ bếp:tư vấn thiết kế, thiết kế nội thất','string','tintuc'],

        //common
        ['description','Description site','Bếp Xinh thiết kế nội thất, sản xuất nội thất, tủ bếp chất lượng cao, tư vấn thiết kế trang trí nội thất nội thất nhà ở, bếp','Bếp Xinh thiết kế nội thất, sản xuất nội thất, tủ bếp chất lượng cao, tư vấn thiết kế trang trí nội thất nội thất nhà ở, bếp','string','common'],
        ['directorpluginviewr','Thư mục Plugin Viewer','Thư mục Plugin Viewer','/../../../../Pluginview/src/Pluginview/','string','common'],
        ['fileextupload','File EXT','Các file được phép upload:gif,jpg,jpeg,png','gif,jpg,jpeg,png','string','common'],
        ['googleanalytic','Googleanalytic','Mã Google Analytic','','string','common'],
        ['headtitle','Head Title','Nội thất Bếp Xinh, Thiết kế và sản xuất tủ bếp cao cấp, thi công nội thất đẹp','Nội thất Bếp Xinh, Thiết kế và sản xuất tủ bếp cao cấp, thi công nội thất đẹp','string','common'],
        ['keywords','Keywords','','','string','common'],
        ['loadingimg','Ảnh Loading','Hình ảnh hiện thị loading','http://www.bepxinh.vn/public/loading.gif','string','common'],
        ['titlesite','Title Website','Nội thất Bếp Xinh, Thiết kế và sản xuất tủ bếp cao cấp, thi công nội thất đẹp','Nội thất Bếp Xinh, Thiết kế và sản xuất tủ bếp cao cấp, thi công nội thất đẹp','string','common'],
        ['trademark','Dòng Trademark','Dòng Trademark: Logo và Thương hiệu - BEPXINH.VN đã được bảo hộ','Logo và Thương hiệu - BEPXINH.VN đã được bảo hộ','string','common'],
        ['showfirstfooter','Hiện thị First Footer','Soạn thảo tại Footer:firstfooter.phtml',1,'boolean','common'],
        ['cateservice','Category Service','Category tích hợp với Service',87,'integer','common'],
        ['navafter','Nav After','Cột menu trước hay sau?',false,'boolean','common'],
        ['templateservice','Template Service','HTML Template for Service','services/main-services/index.phtml','string','common'],
        ['columnright','Cột Phải','Cột đứng ở phải hay trái',false,'boolean','common'],
        ['notdirectorynumber','Not User DiNum','Không sử dụng số cho Thư mục',false,'boolean','common'],
        ['nogooglefont','No Google Font','Không load google font',false,'boolean','common'],
        ['isnguoinoitieng','WebNNT','Web Nguoinoitieng',false,'boolean','common'],
        ['showsocialmedia','Hiện thị đăng nhập Social','Hiện thị đăng nhập Social',false,'boolean','common'],
        ['allowregister','Cho phép đăng ký','Cho phép đăng ký thành viên mới',false,'boolean','common'],
        ['allowregisteronlyfromsocial','Cho phép đăng ký chỉ từ Social','Chỉ đăng ký từ Fb, Gg, Twitter',false,'boolean','common'],
        ['tagcategory','Hiện thị Tag Category','Hiện thị Tag Category thay cho Category Thường',false,'boolean','common'],
        ['allownewtag','Cho phép tạo tag mới từ CSM Edit','True thì có thể thêm tag mới, nếu không chỉ sử dụng tag đã có, hoặc hệ thống không sử dụng bảng tag',false,'boolean','common'],
        ['www','Trang chính có WWW','Sử dụng để xác định canonical',false,'boolean','common'],




    ];
    /**
     * @var Groups
     */
    protected $grouptable = null;

    /**
     * @var Options
     */
    protected $optionstable = null;

    /**
     * @var OptionManager
     */
    protected $optionmanager = null;


    public function __invoke($sm)
    {
        $this->grouptable = $sm->get(Groups::class);
        $this->optionstable = $sm->get(Options::class);
        $this->optionmanager = $sm->get(OptionManager::class);

        return $this->init($sm);
    }

    public function indexAction()
    {

        $path = realpath($this->directorybackup );
        $fileinfo = [];

        if ($path !== false) {
            $files = scandir($path);
            foreach ($files as $file) {
                $fullpath = $path . '/' . $file;
                if (is_file($fullpath)) {
                    $names = explode('.', $file);

                    $info = [
                        'file' => $file,
                        'date' => date('Y-m-d h:s', $names[0])
                    ];
                    $fileinfo[] = $info;
                }

            }
        }





        $this->check_add_group_options();
        $this->check_add_options();

        $listoption = $this->optionmanager->listoptions();


        return [
            'listoption' => $listoption,
            'groups'=>$this->grouptable->select(),
            'listfile' => $fileinfo
        ];
    }

    public function addgroupAction()
    {
        $request    = $this->getRequest();
        $form       = new GroupoptionForm('formgroup', $this->dbAdapter);
        $form->get('submit')->setValue('Add');

        if ($request->isPost()) {

            $group = new Group();
            $form->setData($request->getPost());

            if ($form->isValid())
            {
                $group->exchangeArray($form->getData());
                $this->grouptable->insert($group->getArrayCopy());
                return $this->redirect()->toRoute('ahdconfig');
            }
        }

        return ['form' => $form];
    }

    public function editgroupAction()
    {
        $request = $this->getRequest();
        $form = new GroupoptionForm('edit', $this->dbAdapter);
        $form->get('submit')->setValue('Update');
        $id = $request->getQuery('id','');
        $tableoption = $this->grouptable;
        $gr = $tableoption->find($id);

        if ($gr == null)
            return $this->returnRedirect('Không tồn tại (1)');



        if ($request->isPost()) {

            $group = new Group();
            $button = $request->getPost('submit','Update');

            if ($button=='Update') {
                $form->setData($request->getPost());
                if ($form->isValid()) {
                    $group->exchangeArray($form->getData());
                    $this->grouptable->update($group->getArrayCopy(), ['id' => $group->getId()]);
                    return $this->redirect()->toRoute('ahdconfig');
                }
            }
            else if ($button=="Delete") return $this->redirect()->toRoute('ahdconfig', ['action'=>'delgroup'], ['query'=> ['id'=>$id]]);

        }
        else $form->bind($gr);
        $form->get('id')->setAttribute('readonly','true') ;

        return ['form' => $form];

    }

    public function delgroupAction()
    {
        $request = $this->getRequest();
        $id = $request->getQuery('id','');
        $tableoption = $this->grouptable;
        $gr = $tableoption->select(['id' => $id])->current();
        if ($gr == null)
            return $this->returnRedirect('Không tồn tại');

        if ($request->isPost()) {
            $tableoption->delete(['id' => $id]);
            return $this->redirect()->toRoute('ahdconfig');

        }

        return ['name'=>$gr->getId()];

    }

    public function confAction()
    {
        $params = $this->params();
        $idgroup = $params->fromQuery('id','');
        $group = $this->grouptable->find($idgroup);

        if ($group == null)
        {
            $url = $this->plugin('url')->fromRoute('ahdconfig', [], ['force_canonical' => true]);
            return $this->returnRedirect(['Thiết lập này không có'],'Lỗi',$url,20);
        }


        if ($this->getRequest()->isPost())
        {

            $update = $this->getRequest()->getPost();
            foreach ($update as $k => $v)
            {
                $this->optionstable->saveOptionValue($k,$v);
            }
            $this->buildconfig();
        }

        $options = $this->optionstable->select(['group_id'=>$idgroup]);

        return ['group'=>$group,'options'=>$options];

    }

    public function buildconfig()
    {
        $this->optionmanager->buildconfig();
    }

    public function addoptionAction()
    {
        $params  = $this->params();
        $idgroup = $params->fromQuery('groupoption','');
        $request = $this->getRequest();


        $group = $this->grouptable->find($idgroup);

        if ($group == null)
        {
            $url = $this->plugin('url')->fromRoute('ahdconfig', [], ['force_canonical' => true]);
            return $this->returnRedirect($url, ['Không thấy thiết lập']);
        }



        $form = new OptionForm();

        $form->get('group_id')->setValue($group->id);
        $form->get('submit')->setValue('Add');



        if ($request->isPost()) {

            $option = new Option();
            $filter = $option->getInputFilter();
            $validatorchain = $filter->get('option_id')->getValidatorChain();

            //Them kiem tra ID da co trong db
            $validator_checkdatabase = new NoRecordExists(
                [
                    'table'   => 'ahdoption',
                    'field'   => 'option_id',
                    'adapter' => $this->dbAdapter
                ]
            );
            $validatorchain->addValidator($validator_checkdatabase);



            $form->setInputFilter($filter);
            //option_id
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $option->exchangeArray($form->getData());
                if ($option->option_type == 'boolean') $option->option_value = 0; else $option->option_value='';
                $is = $option->getArrayCopy();

                $this->optionstable->insert($is);
                return $this->redirect()->toRoute('ahdconfig', ['action'=>'conf'], ['query'=> ['id'=>$group->id]]);
            }
        }

        return ['form' => $form,'group'=>$group];
    }

    public function editoptionAction()
    {
        $params = $this->params();
        $idgroup = $params->fromQuery('groupoption','');
        $group = $this->grouptable->find($idgroup);

        if ($idgroup == null)
        {
            $url = $this->plugin('url')->fromRoute('ahdconfig', [], ['force_canonical' => true]);
            return $this->returnRedirect($url, ['Không thấy thiết lập']);
        }

        $idoption = $params->fromQuery('optionid','');
        $option = $this->optionstable->find($idoption);

        if ($option == null)
        {
            $url = $this->plugin('url')->fromRoute('ahdconfig', [], ['force_canonical' => true]);
            return $this->returnRedirect($url, ['Không thấy thiết lập']);
        }




        $request = $this->getRequest();
        $form = new OptionForm();

        $form->get('group_id')->setValue($group->id);
        $form->get('submit')->setValue('Cập nhật');

        if ($request->isPost()) {

            $option = new Option();
            $filter = $option->getInputFilter();
            $validatorchain = $filter->get('option_id')->getValidatorChain();

            //Them kiem tra ID da co trong db
            $validator_checkdatabase = new RecordExists(
                [
                    'table'   => 'ahdoption',
                    'field'   => 'option_id',
                    'adapter' => $this->dbAdapter
                ]
            );
            $validatorchain->addValidator($validator_checkdatabase);







            $form->setInputFilter($filter);
            //option_id


            $form->setData($request->getPost());



            $form->get('option_id')->setValue($option->option_id);
            if ($form->isValid()) {

                $option->exchangeArray($form->getData());


                $this->optionstable->update($option->getArrayCopy(), ['option_id' => $option->getOptionId()]);
                return $this->redirect()->toRoute('ahdconfig', ['action'=>'conf'], ['query'=> ['id'=>$group->id]]);
            }
            else
            {
                var_dump($form->getMessages());
            }
        }
        else
            $form->bind($option);
        $form->get('option_id')->setAttribute('readonly',true);
        $view = new ViewModel(['form' => $form,'group'=>$group,'option'=>$option]);
        $view->setTemplate('ichte/option/option/addoption');
        return $view;

    }


    public function deleteoptionAction()
    {
        $params     = $this->params();
        $idoption   = $params->fromQuery('idoption','');
        $option = $this->optionstable->find($idoption);

        if ($option == null)
        {
            $url = $this->plugin('url')->fromRoute('ahdconfig', [], ['force_canonical' => true]);
            return $this->returnRedirect($url, ['Không thấy thiết lập']);
        }
        $request = $this->getRequest();
        if ($request->isPost()) {
            $this->optionstable->delete(['option_id' => $idoption]);
            return $this->redirect()->toRoute('ahdconfig', ['action'=>'conf'], ['query'=> ['id'=>$option->getGroupId()]]);


        }
        $url = $this->url()->fromRoute('ahdconfig', ['action'=>'deleteoption'], ['query'=> ['idoption'=>$option->option_id]]);
        $elements = [
            'option_id'=>$option->option_id,
            'option_name'=>$option->option_name
        ];
        return $this->askBeforeDone("Có chắc chắn xóa Option [$option->option_id] ?",$url,$elements);

    }

    public function removebackupAction() {
        $file = $this->params()->fromQuery('id', null);
        $fullfile = realpath($this->directorybackup.'/'.$file);
        @unlink($fullfile);
        return $this->redirect()->toRoute('ahdconfig');
    }


    public function removeallbackupAction() {
        $path = realpath($this->directorybackup );
        if ($path !== false) {
            $files = scandir($path);
            $fileinfo = [];
            foreach ($files as $file) {
                $fullpath = $path . '/' . $file;
                if (is_file($fullpath)) {
                    @unlink($fullpath);
                }

            }
        }

        return $this->redirect()->toRoute('ahdconfig');
    }


    public function restorebackupAction() {
        $file = $this->params()->fromQuery('id', null);
        $fullfile = realpath($this->directorybackup.'/'.$file);
        $content = file_get_contents($fullfile);
        $arr = include $fullfile;

        $groups = $arr['AHD'];
        foreach ($groups as $group => $options) {
            //Restore group if not exist
            if (!$this->dbAdapter->existrow()) {
                $this->dbAdapter->insert_row(
                    [
                        'id' => $group,
                        'name' => $group . ' backup'
                    ],
                    'ahdoption_group'
                );
            }

            foreach ($options as $idoption => $option) {
                if (!$this->dbAdapter->existrow()) {
                    $this->dbAdapter->insert_row(
                        [
                            'option_id' => $idoption,
                            'option_name' => $idoption . ' backup',
                            'option_des' => '',
                            'option_value' => $option,
                            'option_type' => 'string',
                            'group_id' => $group
                        ],
                        'ahdoption'
                    );
                }

                //Update Backup
                $this->dbAdapter->update_row_values(
                    [
                        'option_value' => $option
                    ],
                    [ 'option_id' => $idoption], 'ahdoption');

            }
         }
        return $this->redirect()->toRoute('ahdconfig');

    }











    public function resetallAction()
    {
        if ($this->getRequest()->getPost('okreset','')=='okreset')
        {

            @mkdir('config/ahd.config.backup');
            $backupfile = 'config/ahd.config.backup/'.time().'.bak';
            @copy('config/ahd.config.php', $backupfile);

            $this->dbAdapter->execute("DROP TABLE ahdoption_group");
            $this->dbAdapter->execute("DROP TABLE ahdoption");



            return $this->redirect()->toRoute('ahdconfig');
        }
        return $this->askBeforeDone('Có hủy bỏ hết các thiết lập, mọi thiết lập trở về mặc định của Bếp Xinh.VN',null,['okreset'=>'okreset']);
    }

    //Test if option not exist in database funtion will add option to ...
    function check_add_options()
    {
        foreach ($this->options_default as $item) {
            if (!$this->dbAdapter->existrow(['option_id'=>$item[0]],'ahdoption'))
            {
                $values = [
                    'option_id'=>$item['0'],
                    'option_name'=>$item['1'],
                    'option_des'=>$item['2'],
                    'option_value'=>$item['3'],
                    'option_type'=>$item['4'],
                    'group_id'=>$item['5']

                ];

                $this->dbAdapter->insert_row($values,'ahdoption');
            }
        }
    }

    //Create table option
    function check_add_group_options()
    {
        if (count($this->dbAdapter->execute("SHOW TABLES LIKE 'ahdoption_group'"))==0)
        {
           $this->dbAdapter->execute("
                CREATE TABLE `ahdoption_group` (
                      `id` varchar(50) NOT NULL,
                      `name` varchar(75) NOT NULL,
                      PRIMARY KEY (`id`)
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
            $this->dbAdapter->execute("
                 CREATE TABLE `ahdoption` (
                  `option_id` varchar(50) NOT NULL,  
                  `option_name` varchar(250) NOT NULL,  
                  `option_des` varchar(250) NOT NULL,  
                  `option_value` varchar(500) NOT NULL,
                  `option_type` enum('string','integer','numeric','array','boolean','positive_integer','unsigned_integer','unsigned_numeric') NOT NULL,
                  `group_id` varchar(50) NOT NULL DEFAULT '',
                  PRIMARY KEY (`option_id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
                                   
            $qr ="
                INSERT INTO `ahdoption_group` (`id`, `name`) VALUES ('common', 'Các thiết lập chung cho Website');       
                INSERT INTO `ahdoption_group` (`id`, `name`) VALUES ('tintuc', 'Cấu hình mục tin tức');
                INSERT INTO `ahdoption_group` (`id`, `name`) VALUES ('product', 'Cấu hình sản phẩm');
                INSERT INTO `ahdoption_group` (`id`, `name`) VALUES ('contactsite', 'Cấu hình liên hệ');
                ";
              $ar = explode("\r",$qr);
              foreach($ar as $qr)
              {
               $qr = trim($qr); 
                if (strlen($qr)>10)
                {
                    $this->dbAdapter->execute($qr);
                }
              }
        }

       
    }
        
    public function pluginviewerAction()
    {
        $request = $this->getRequest();
        if ($request->isPost())
        {
            $plugin = $this->params()->fromPost('plugin',''); 
            $ext = pathinfo($plugin, PATHINFO_EXTENSION);
            $base = basename($plugin,$ext);
            $dir = dirname($plugin).'/';
            if ($ext == 'php')
            {
                $newplugin = $dir.$base.'php_disenable';
            }
            else 
                $newplugin = $dir.$base.'php';
            rename($plugin,$newplugin); 
            return $this->getResponse()->setContent('OK');
        }
        
        $options = \Common::$cf->AHD->common;
        $dir = __DIR__.$options->directorpluginviewr;
        
        $ar = [];
        $setfile = function($file) use (&$ar)
        {
            $ar[] = [
                'file' => $file->getPathname(),
                'name' => explode('.',$file->getBasename())[0],
                'ext'  => $file->getExtension()
            ];
        };
        
        foreach (new \DirectoryIterator($dir) as $fileInfo) {
            if($fileInfo->isDot()) continue;
            if ($fileInfo->isDir())
            {
                foreach (new \DirectoryIterator($fileInfo->getPathname()) as $fileInfonext)
                {
                    if($fileInfonext->isDot()) continue;
                    $setfile($fileInfonext);
                } 
            }
            else
            //echo $fileInfo->getFilename() . "<br>\n";
            $setfile($fileInfo);
            
        }
        
        return ['plugins'=>$ar];
        

    }
    






    
    public function urlservicetoolAction()
    {
        if ($this->getRequest()->isPost())
        {
            $content = $this->params()->fromPost('content','');
            $byte = file_put_contents('config/autoload/service.php',$content);
            if ($byte === false)  throw new \Exception("Can not save: config/autoload/service.php");
        }
        
        return ['content'=>file_get_contents('config/autoload/service.php')];
        
    }   
    
}