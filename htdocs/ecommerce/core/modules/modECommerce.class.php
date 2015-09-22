<?php
/*
 * @module		ECommerce
 * @version		1.4
 * @copyright	Auguria
 * @author		<franck.charpentier@auguria.net>
 * @licence		GNU General Public License
 */
if (!defined('DOL_CLASS_PATH'))
	define('DOL_CLASS_PATH', null);

require_once(DOL_DOCUMENT_ROOT .'/core/modules/DolibarrModules.class.php');

dol_include_once('/ecommerce/admin/class/gui/eCommerceMenu.class.php');
dol_include_once('/ecommerce/admin/class/data/eCommerceDict.class.php');

if (DOL_CLASS_PATH == null)
	require_once(DOL_DOCUMENT_ROOT.'/societe.class.php');
else
	require_once(DOL_DOCUMENT_ROOT.'/societe/'.DOL_CLASS_PATH.'societe.class.php');

/**
 *  Description and activation class for module ECommerce
 */
class modECommerce extends DolibarrModules
{
	/**
	 *   \brief      Constructor. Define names, constants, directories, boxes, permissions
	 *   \param      DB      Database handler
	 */
	function modECommerce($DB)
	{
		$this->db = $DB;

		// Id for module (must be unique).
		// Use here a free id (See in Home -> System information -> Dolibarr for list of used modules id).
		$this->numero = 107100;
		// Key text used to identify module (for permissions, menus, etc...)
		$this->rights_class = 'eCommerce';

		// Family can be 'crm','financial','hr','projects','products','ecm','technic','other'
		// It is used to group modules in module setup page
		$this->family = "other";
		// Module label (no space allowed), used if translation string 'ModuleXXXName' not found (where XXX is value of numeric property 'numero' of module)
		$this->name = preg_replace('/^mod/i','',get_class($this));
		// Module description, used if translation string 'ModuleXXXDesc' not found (where XXX is value of numeric property 'numero' of module)
		$this->description = "Module for synchronise Dolibarr with ECommerce platform";
		// Possible values for version are: 'development', 'experimental', 'dolibarr' or version
		$this->version = '1.5.1.3.8';
		// Key used in llx_const table to save module status enabled/disabled (where MYMODULE is value of property name of module in uppercase)
		$this->const_name = 'MAIN_MODULE_'.strtoupper($this->name);
		// Where to store the module in setup page (0=common,1=interface,2=others,3=very specific)
		$this->special = 1;
		// Name of image file used for this module.
		// If file is in theme/yourtheme/img directory under name object_pictovalue.png, use this->picto='pictovalue'
		// If file is in module/images directory, use this->picto=DOL_URL_ROOT.'/module/images/file.png'
		$this->picto='eCommerce.png@ecommerce';
                
                // Defined all module parts (triggers, login, substitutions, menus, css, etc...)
		// for default path (eg: /mymodule/core/xxxxx) (0=disable, 1=enable)
		// for specific path of parts (eg: /mymodule/core/modules/barcode)
		// for specific css file (eg: /mymodule/css/mymodule.css.php)
		//$this->module_parts = array(
		//                        	'triggers' => 0,                                 // Set this to 1 if module has its own trigger directory
		//							'login' => 0,                                    // Set this to 1 if module has its own login method directory
		//							'substitutions' => 0,                            // Set this to 1 if module has its own substitution function file
		//							'menus' => 0,                                    // Set this to 1 if module has its own menus handler directory
		//							'barcode' => 0,                                  // Set this to 1 if module has its own barcode directory
		//							'models' => 0,                                   // Set this to 1 if module has its own models directory
		//							'css' => '/mymodule/css/mymodule.css.php',       // Set this to relative path of css if module has its own css file
		//							'hooks' => array('hookcontext1','hookcontext2')  // Set here all hooks context managed by module
		//							'workflow' => array('order' => array('WORKFLOW_ORDER_AUTOCREATE_INVOICE')) // Set here all workflow context managed by module
		//                        );
		$this->module_parts = array(
                        'triggers' => 1
                );

		// Data directories to create when module is enabled.
		// Example: this->dirs = array("/mymodule/temp");
		$this->dirs = array();
		$r=0;

		// Relative path to module style sheet if exists. Example: '/mymodule/mycss.css'.
		$this->style_sheet = '';

		// Config pages. Put here list of php page names stored in admmin directory used to setup module.
		$this->config_page_url = array('eCommerceSetup.php@ecommerce');

		// Dependencies
		$this->depends = array("modExpedition","modFacture","modCommande","modSociete","modProduit","modCategorie","modWebServices");		// List of modules id that must be enabled if this module is enabled
		$this->requiredby = array();	// List of modules id to disable if this one is disabled
		$this->phpmin = array(4,3);					// Minimum version of PHP required by module
		$this->need_dolibarr_version = array(2,7);	// Minimum version of Dolibarr required by module
		$this->langfiles = array("ecommerce@ecommerce");

		// Constants
		$this->const = array();			// List of particular constants to add when module is enabled
		//Example: $this->const=array(0=>array('MYMODULE_MYNEWCONST1','chaine','myvalue','This is a constant to add',0),
		//                            1=>array('MYMODULE_MYNEWCONST2','chaine','myvalue','This is another constant to add',0) );

		// Array to add new pages in new tabs
		//$this->tabs = array('entity:Title:@mymodule:/mymodule/mynewtab.php?id=__ID__');
		// where entity can be
		// 'thirdparty'       to add a tab in third party view
		// 'intervention'     to add a tab in intervention view
		// 'supplier_order'   to add a tab in supplier order view
		// 'supplier_invoice' to add a tab in supplier invoice view
		// 'invoice'          to add a tab in customer invoice view
		// 'order'            to add a tab in customer order view
		// 'product'          to add a tab in product view
		// 'propal'           to add a tab in propal view
		// 'member'           to add a tab in fundation member view
		// 'contract'         to add a tab in contract view


		// Boxes
		$this->boxes = array();			// List of boxes
		$r=0;

		// Add here list of php file(s) stored in includes/boxes that contains class to show a box.
		// Example:
		//$this->boxes[$r][1] = "myboxa.php";
		//$r++;
		//$this->boxes[$r][1] = "myboxb.php";
		//$r++;


		// Permissions
		$this->rights = array();		// Permission array used by this module
		$this->rights_class = 'ecommerce';
		$r=0;

		$r++;
		$this->rights[$r][0] = 107101;
		$this->rights[$r][1] = 'See synchronization status';
		$this->rights[$r][3] = 0;
		$this->rights[$r][4] = 'read';

		$r++;
		$this->rights[$r][0] = 107102;
		$this->rights[$r][1] = 'Synchronize';
		$this->rights[$r][3] = 0;
		$this->rights[$r][4] = 'write';

		$r++;
		$this->rights[$r][0] = 107103;
		$this->rights[$r][1] = 'Configure websites';
		$this->rights[$r][3] = 0;
		$this->rights[$r][4] = 'site';
		
		$r=0;

		// Add here list of permission defined by an id, a label, a boolean and two constant strings.
		// Example:
		// $this->rights[$r][0] = 2000; 				// Permission id (must not be already used)
		// $this->rights[$r][1] = 'Permision label';	// Permission label
		// $this->rights[$r][3] = 1; 					// Permission by default for new user (0/1)
		// $this->rights[$r][4] = 'level1';				// In php code, permission will be checked by test if ($user->rights->permkey->level1->level2)
		// $this->rights[$r][5] = 'level2';				// In php code, permission will be checked by test if ($user->rights->permkey->level1->level2)
		// $r++;


		// Main menu entries
		$this->menus = array();			// List of menus to add
		$r=0;

		// Add here entries to declare new menus
		$eCommerceMenu = new eCommerceMenu($this->db,null,$this);
		$this->menu = $eCommerceMenu->getMenu();
//		// Example to declare the Top Menu entry:
//		 $this->menu[$r]=array(	'fk_menu'=>0,			// Put 0 if this is a top menu
//									'type'=>'top',			// This is a Top menu entry
//									'titre'=>'ECommerceMenu',
//									'mainmenu'=>'eCommerce',
//									'leftmenu'=>'1',		// Use 1 if you also want to add left menu entries using this descriptor. Use 0 if left menu entries are defined in a file pre.inc.php (old school).
//									'url'=>'/ecommerce/index.php',
//									'langs'=>'ecommerce@ecommerce',	// Lang file to use (without .lang) by module. File must be in langs/code_CODE/ directory.
//									'position'=>100,
//									'enabled'=>'1',			// Define condition to show or hide menu entry. Use '$conf->mymodule->enabled' if entry must be visible if module is enabled.
//									'perms'=>'1',			// Use 'perms'=>'$user->rights->mymodule->level1->level2' if you want your menu with a permission rules
//									'target'=>'',
//									'user'=>2);				// 0=Menu for internal users, 1=external users, 2=both
//		 $r++;
//		//
//		// Example to declare a Left Menu entry:
//		 $this->menu[$r]=array(	'fk_menu'=>'r=0',		// Use r=value where r is index key used for the parent menu entry (higher parent must be a top menu entry)
//									'type'=>'left',			// This is a Left menu entry
//									'titre'=>'ECommerceMenu',
//									'mainmenu'=>'eCommerce',
//									'url'=>'/ecommerce/index.php',
//									'langs'=>'ecommerce@ecommerce',	// Lang file to use (without .lang) by module. File must be in langs/code_CODE/ directory.
//									'position'=>100,
//									'enabled'=>'1',			// Define condition to show or hide menu entry. Use '$conf->mymodule->enabled' if entry must be visible if module is enabled.
//									'perms'=>'1',			// Use 'perms'=>'$user->rights->mymodule->level1->level2' if you want your menu with a permission rules
//									'target'=>'',
//									'user'=>2);				// 0=Menu for internal users, 1=external users, 2=both


		// Exports
		$r=1;

		// Example:
		// $this->export_code[$r]=$this->rights_class.'_'.$r;
		// $this->export_label[$r]='CustomersInvoicesAndInvoiceLines';	// Translation key (used only if key ExportDataset_xxx_z not found)
		// $this->export_permission[$r]=array(array("facture","facture","export"));
		// $this->export_fields_array[$r]=array('s.rowid'=>"IdCompany",'s.nom'=>'CompanyName','s.address'=>'Address','s.cp'=>'Zip','s.ville'=>'Town','s.fk_pays'=>'Country','s.tel'=>'Phone','s.siren'=>'ProfId1','s.siret'=>'ProfId2','s.ape'=>'ProfId3','s.idprof4'=>'ProfId4','s.code_compta'=>'CustomerAccountancyCode','s.code_compta_fournisseur'=>'SupplierAccountancyCode','f.rowid'=>"InvoiceId",'f.facnumber'=>"InvoiceRef",'f.datec'=>"InvoiceDateCreation",'f.datef'=>"DateInvoice",'f.total'=>"TotalHT",'f.total_ttc'=>"TotalTTC",'f.tva'=>"TotalVAT",'f.paye'=>"InvoicePaid",'f.fk_statut'=>'InvoiceStatus','f.note'=>"InvoiceNote",'fd.rowid'=>'LineId','fd.description'=>"LineDescription",'fd.price'=>"LineUnitPrice",'fd.tva_tx'=>"LineVATRate",'fd.qty'=>"LineQty",'fd.total_ht'=>"LineTotalHT",'fd.total_tva'=>"LineTotalTVA",'fd.total_ttc'=>"LineTotalTTC",'fd.date_start'=>"DateStart",'fd.date_end'=>"DateEnd",'fd.fk_product'=>'ProductId','p.ref'=>'ProductRef');
		// $this->export_entities_array[$r]=array('s.rowid'=>"company",'s.nom'=>'company','s.address'=>'company','s.cp'=>'company','s.ville'=>'company','s.fk_pays'=>'company','s.tel'=>'company','s.siren'=>'company','s.siret'=>'company','s.ape'=>'company','s.idprof4'=>'company','s.code_compta'=>'company','s.code_compta_fournisseur'=>'company','f.rowid'=>"invoice",'f.facnumber'=>"invoice",'f.datec'=>"invoice",'f.datef'=>"invoice",'f.total'=>"invoice",'f.total_ttc'=>"invoice",'f.tva'=>"invoice",'f.paye'=>"invoice",'f.fk_statut'=>'invoice','f.note'=>"invoice",'fd.rowid'=>'invoice_line','fd.description'=>"invoice_line",'fd.price'=>"invoice_line",'fd.total_ht'=>"invoice_line",'fd.total_tva'=>"invoice_line",'fd.total_ttc'=>"invoice_line",'fd.tva_tx'=>"invoice_line",'fd.qty'=>"invoice_line",'fd.date_start'=>"invoice_line",'fd.date_end'=>"invoice_line",'fd.fk_product'=>'product','p.ref'=>'product');
		// $this->export_alias_array[$r]=array('s.rowid'=>"socid",'s.nom'=>'soc_name','s.address'=>'soc_adres','s.cp'=>'soc_zip','s.ville'=>'soc_ville','s.fk_pays'=>'soc_pays','s.tel'=>'soc_tel','s.siren'=>'soc_siren','s.siret'=>'soc_siret','s.ape'=>'soc_ape','s.idprof4'=>'soc_idprof4','s.code_compta'=>'soc_customer_accountancy','s.code_compta_fournisseur'=>'soc_supplier_accountancy','f.rowid'=>"invoiceid",'f.facnumber'=>"ref",'f.datec'=>"datecreation",'f.datef'=>"dateinvoice",'f.total'=>"totalht",'f.total_ttc'=>"totalttc",'f.tva'=>"totalvat",'f.paye'=>"paid",'f.fk_statut'=>'status','f.note'=>"note",'fd.rowid'=>'lineid','fd.description'=>"linedescription",'fd.price'=>"lineprice",'fd.total_ht'=>"linetotalht",'fd.total_tva'=>"linetotaltva",'fd.total_ttc'=>"linetotalttc",'fd.tva_tx'=>"linevatrate",'fd.qty'=>"lineqty",'fd.date_start'=>"linedatestart",'fd.date_end'=>"linedateend",'fd.fk_product'=>'productid','p.ref'=>'productref');
		// $this->export_sql_start[$r]='SELECT DISTINCT ';
		// $this->export_sql_end[$r]  =' FROM ('.MAIN_DB_PREFIX.'facture as f, '.MAIN_DB_PREFIX.'facturedet as fd, '.MAIN_DB_PREFIX.'societe as s)';
		// $this->export_sql_end[$r] .=' LEFT JOIN '.MAIN_DB_PREFIX.'product as p on (fd.fk_product = p.rowid)';
		// $this->export_sql_end[$r] .=' WHERE f.fk_soc = s.rowid AND f.rowid = fd.fk_facture';
		// $r++;
	}

	/**
	 *		\brief      Function called when module is enabled.
	 *					The init function add constants, boxes, permissions and menus (defined in constructor) into Dolibarr database.
	 *					It also creates data directories.
	 *      \return     int             1 if OK, 0 if KO
	 */
	function init()
	{
		$sql = array();

		$result=$this->load_tables();
		$this->addSettlementTerms();
		$this->addAnonymousCompany();
		return $this->_init($sql);
	}

	/**
	 *		\brief		Function called when module is disabled.
	 *              	Remove from database constants, boxes and permissions from Dolibarr database.
	 *					Data directories are not deleted.
	 *      \return     int             1 if OK, 0 if KO
	 */
	function remove()
	{
		$sql = array();

		return $this->_remove($sql);
	}


	/**
	 *		\brief		Create tables, keys and data required by module
	 * 					Files llx_table1.sql, llx_table1.key.sql llx_data.sql with create table, create keys
	 * 					and create data commands must be stored in directory /mymodule/sql/
	 *					This function is called by this->init.
	 * 		\return		int		<=0 if KO, >0 if OK
	 */
	function load_tables()
	{
		return $this->_load_tables('/ecommerce/sql/');
	}
	
	/**
	 * Add anonymous company for anonymous orders
	 */
	private function addAnonymousCompany()
	{
	    global $user;
	    
		$idCompany = dolibarr_get_const($this->db, 'ECOMMERCE_COMPANY_ANONYMOUS');
		
		// Check for const existing but company deleted from dB
		if ($idCompany)
		{
			$dBSociete = new Societe($this->db);
			$idCompany = $dBSociete->fetch($idCompany) < 0 ? null:$idCompany ; 
		}
		
		if ($idCompany == null)
		{
			$dBSociete = new Societe($this->db);
			$dBSociete->nom = 'Anonymous';
			$dBSociete->client = 3;//for client/prospect
			$dBSociete->create($user);
			
			if (dolibarr_set_const($this->db, 'ECOMMERCE_COMPANY_ANONYMOUS', $dBSociete->id) < 0)
			{
				dolibarr_print_error($this->db);
			}
		}
	}
	
	/**
	 * Add settlement terms if not exists
	 */
	private function AddSettlementTerms()
	{
		$table = MAIN_DB_PREFIX."c_payment_term";
		$eCommerceDict = new eCommerceDict($this->db, $table);
		$cashExists = $eCommerceDict->fetchByCode('CASH');
		if ($cashExists == array())
		{
			// Get free rowid to insert
			$newid = 0;
			$sql = "SELECT max(`rowid`) newid from `".$table."`";
			$maxId = $this->db->query($sql);
			if ($maxId)
			{
				$obj = $this->db->fetch_object($maxId);
				$newid = ($obj->newid + 1);	
			}
			else
			{
				dol_print_error($this->db);
			}
			
			// Get free sortorder to insert
			$newSort = 0;
			$sql = "SELECT max(`sortorder`) newsortorder from `".$table."`";
			$maxSort = $this->db->query($sql);
			if ($maxSort)
			{
				$obj = $this->db->fetch_object($maxSort);
				$newSort = ($obj->newsortorder + 1);	
			}
			else
			{
				dol_print_error($this->db);
			}
			
			if ($newid != 0 && $newSort != 0)
			{
				$sql = "INSERT INTO `".$table."`
							(`rowid`, `code`, `sortorder`, `active`, `libelle`, `libelle_facture`, `fdm`, `nbjour`, `decalage`)
						VALUES
							(".$newid.", 'CASH', ".$newSort.", 1, 'Au comptant', 'A la commande', 0, 0, NULL)";
				$insert = $this->db->query($sql);
			}
		}
	}
}

?>