<?
require_once __DIR__.'/dbHelper.class.php';

class exampleModel extends dbHelper {

  public $SQL_PREPS = [];

  public $SQL = [
    'createTable' => "CREATE TABLE IF NOT EXISTS `cli_test` (
        `ID` int(11) NOT NULL AUTO_INCREMENT,
        `title` varchar(254) NOT NULL DEFAULT '',
        `number` INT(10) DEFAULT NULL,
        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (`ID`)
      ) ENGINE=MyISAM AUTO_INCREMENT=59 DEFAULT CHARSET=latin1;",
    'testInsert' => "INSERT INTO cli_test (title,number) VALUES (%s,%d)",
    'testSelect' => "SELECT * FROM cli_test",
    'removeTable' => "DROP TABLE cli_test"
  ];

  public function __construct()
	{
		add_action('admin_menu', array( $this, 'init_page') );

	}

	public function init_page()
	{
		// --> Menu hooks
		add_menu_page( 'Test Page', 'Test Page', 'manage_options', 'test_page', array( $this, 'page'));
	}

  // /htdocs/testtheme.com/wpcli exampleModel test cooltitle 234324
  function test($title,$number) {
    $this->createTable();
    $this->testInsert($title,$number);
    $select = $this->testSelect();
    $this->removeTable();
    return $select;
  }


  public function page()
	{
		?>
    <h1>Test Page</h1>
    <div md-whiteframe="1" ng-cloak ng-controller="testPage">

    </div>


		<script>
		angular.module('app')
			.controller('testPage',
        function($scope,Upload,AJAX,$mdDialog) {

			})
		</script>
    <?
  }
}
