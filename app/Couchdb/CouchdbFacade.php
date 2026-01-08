<?php

namespace App\Couchdb;
use Illuminate\Support\Facades\Facade;

class CouchdbFacade extends Facade
{
    public static function getFacadeAccessor()
    {
        return 'couchdb';
    }
}

// class Couchdb 
// {
// 	/**
// 	 * The Server API Url .
// 	 *
// 	 * @var string
// 	 */
// 	public $server_api_url;
// 	/**
// 	 * The Server Database Username
// 	 *
// 	 * @var bool
// 	 */
// 	private $_my_db_username;
// 	/**
// 	 * The Server Database Password
// 	 *
// 	 * @var bool
// 	 */
// 	private $_my_db_password;
// 	/**
// 	 * Curl Connection instance
// 	 *
// 	 * @var
// 	 */
// 	private $_curl_initialization;
// 	/**
// 	 * Wbb_CouchDB constructor.
// 	 *
// 	 * @param string $server_api_url
// 	 * @param bool   $my_db_username
// 	 * @param bool   $my_db_password
// 	 */
// 	public function __construct ( $server_api_url='http://127.0.0.1:5984', $my_db_username='nic', $my_db_password='nic')
// 	{
// 		// The Server API Url .
// 		$this->server_api_url = $server_api_url;
// 		// The Server Database Username
// 		$this->_my_db_username = $my_db_username;
// 		// The Server Database Password
// 		$this->_my_db_password = $my_db_password;
// 		$this->_curl_initialization = curl_init ();
// 	}
// 	/**
// 	 * Initialize Curl Connection .
// 	 */
// 	 public function sayHello()
//     {
//         echo "Hello, from Facade class.";
//     }
// 	public function InitConnection ()
// 	{
// 		$this->_curl_initialization = curl_init ();
// 	}
// 	/**
// 	 * Close Curl Connection
// 	 */
// 	public function CloseConnection ()
// 	{
// 		curl_close ( $this->_curl_initialization );
// 	}
// 	/**
// 	 * Check if CouchDB is running
// 	 *
// 	 * You can check if CouchDB is running just by executing HTTP GET request at http://127.0.0.1:5984 URL. If all is
// 	 * OK, you should be able to see a “Welcome” message as a part of larger JSON object.
// 	 *
// 	 * @return mixed
// 	 */
// 	public function isRunning ()
// 	{
// 		curl_setopt ( $this->_curl_initialization , CURLOPT_URL , $this->server_api_url );
// 		curl_setopt ( $this->_curl_initialization , CURLOPT_RETURNTRANSFER , TRUE );
// 		curl_setopt (
// 			$this->_curl_initialization , CURLOPT_HTTPHEADER , array (
// 				                            'Content-type: application/json' ,
// 				                            'Accept: */*' ,
// 			                            )
// 		);
// 		$response = curl_exec ( $this->_curl_initialization );
// 		return $response;
// 	}
// 	/**
// 	 * Get a list of databases
// 	 *
// 	 * Try not to get confused with terminology “CouchDB” vs “databases”. CouchDB is a database management system
// 	 * (DMS), which means it can hold multiple databases. A database is a bucket that holds “related data”, such as
// 	 * products.
// 	 *
// 	 */
// 	public function getAllDbs ()
// 	{
// 		curl_setopt ( $this->_curl_initialization , CURLOPT_URL , $this->server_api_url . '/_all_dbs' );
// 		curl_setopt ( $this->_curl_initialization , CURLOPT_RETURNTRANSFER , TRUE );
// 		curl_setopt (
// 			$this->_curl_initialization , CURLOPT_HTTPHEADER , array (
// 				                            'Content-type: application/json' ,
// 				                            'Accept: */*' ,
// 			                            )
// 		);
// 		$response = curl_exec ( $this->_curl_initialization );
// 		return $response;
// 	}
// 	/**
// 	 * @param $db_name
// 	 *
// 	 * Create a database
// 	 *
// 	 * In this example we will create an empty database called customers. For this, we need to use HTTP PUT request.
// 	 *
// 	 * @return mixed
// 	 */
// 	public function createDb ( $db_name )
// 	{

// 		curl_setopt ( $this->_curl_initialization , CURLOPT_URL , $this->server_api_url . '/' . $db_name );
// 		curl_setopt ( $this->_curl_initialization , CURLOPT_CUSTOMREQUEST , 'PUT' );
// 		curl_setopt ( $this->_curl_initialization , CURLOPT_RETURNTRANSFER , TRUE );
// 		if ( $this->_my_db_password && $this->_my_db_username )
// 		{
// 			curl_setopt ( $this->_curl_initialization , CURLOPT_USERPWD , $this->_my_db_username . ':' . $this->_my_db_password );
// 		}
// 		curl_setopt (
// 			$this->_curl_initialization , CURLOPT_HTTPHEADER , array (
// 				                            'Content-type: application/json' ,
// 				                            'Accept: */*' ,
// 			                            )
// 		);
// 		$response = curl_exec ( $this->_curl_initialization );
// 		return $response;
// 	}
// 	/**
// 	 * Get an UUID from CouchDB
// 	 *
// 	 * If you find it impractical to generate your own UUID’s, you can ask CouchDB to give you one. If you need more
// 	 * than one UUID, you can pass in the ?count=5 HTTP parameter to request 5 UUIDs, or any other number you need.
// 	 */
// 	public function GetUUID ()
// 	{

// 		curl_setopt ( $this->_curl_initialization , CURLOPT_URL , $this->server_api_url . '/_uuids' );
// 		curl_setopt ( $this->_curl_initialization , CURLOPT_CUSTOMREQUEST , 'GET' );
// 		curl_setopt ( $this->_curl_initialization , CURLOPT_RETURNTRANSFER , TRUE );
// 		curl_setopt (
// 			$this->_curl_initialization , CURLOPT_HTTPHEADER , array (
// 				                            'Content-type: application/json' ,
// 				                            'Accept: */*' ,
// 			                            )
// 		);
// 		if ( $this->_my_db_password && $this->_my_db_username )
// 		{
// 			curl_setopt ( $this->_curl_initialization , CURLOPT_USERPWD , $this->_my_db_username . ':' . $this->_my_db_password );
// 		}
// 		$response  = curl_exec ( $this->_curl_initialization );
// 		$_response = json_decode ( $response , TRUE );
// 		$UUID = $_response[ 'uuids' ];
// 		return $UUID;
// 	}
// 	/**
// 	 * Create a document
// 	 *
// 	 * To create new document you can either use a POST operation or a PUT operation.
// 	 *
// 	 * In this example we are creating a document (an entry, a row, a record) within the customers database.
// 	 *
// 	 * Each document in CouchDB has an ID. This ID is unique per database. You are free to choose any string to be the
// 	 * ID, although CouchDB recommends a UUID (or GUID). In the example above I showed you how to fetch the UUID from
// 	 * CouchDB itself. Since ID is a required parameter that needs to be passed with create a document request, we can
// 	 * either: request it from CouchDB use some other unique string for it. For our customers table, we will use
// 	 * username field for ID as shown in the example below. Please note that this is not the best decision, as it is
// 	 * recommended to use the UUID for ID. However, for the sake of simplicity and easier overview we will use
// 	 * username.
// 	 *
// 	 *
// 	 * @param array $document_data
// 	 * @param       $database
// 	 * @param       $document
// 	 *
// 	 * @return mixed
// 	 */
// 	public function createDocument ( $document_data = array () , $database , $document )
// 	{

// 		curl_setopt ( $this->_curl_initialization , CURLOPT_URL , $this->server_api_url . '/' . $database . '/' . $document );
// 		curl_setopt ( $this->_curl_initialization , CURLOPT_CUSTOMREQUEST , 'PUT' ); /* or PUT */
// 		curl_setopt ( $this->_curl_initialization , CURLOPT_POSTFIELDS , json_encode ( $document_data ) );
// 		curl_setopt ( $this->_curl_initialization , CURLOPT_RETURNTRANSFER , TRUE );
// 		curl_setopt (
// 			$this->_curl_initialization , CURLOPT_HTTPHEADER , array (
// 				                            'Content-type: application/json' ,
// 				                            'Accept: */*' ,
// 			                            )
// 		);
// 		if ( $this->_my_db_password && $this->_my_db_username )
// 		{
// 			curl_setopt ( $this->_curl_initialization , CURLOPT_USERPWD , $this->_my_db_username . ':' . $this->_my_db_password );
// 		}
// 		$response = curl_exec ( $this->_curl_initialization );
		
// 		return $response;
// 	}
// 	/**
// 	 * Creating a document with attachment
// 	 *
// 	 * CouchDB documents can have attachments. They can be any data (pdf, image, music, video…). It is important to
// 	 * know that attachments are added only to an existing documents. Process of adding an attachment is considered a
// 	 * document update. Since each document update requires a revision number, so does the process of adding
// 	 * attachment.
// 	 *
// 	 * @param $database
// 	 * @param $documentID
// 	 * @param $revision
// 	 * @param $attachment
// 	 *
// 	 * @return mixed
// 	 */
// 	public function createAttachmentDocument ( $database , $documentID , $revision , $attachment )
// 	{
// 		//Database to upload file system.....
// 		$finfo       = finfo_open ( FILEINFO_MIME_TYPE );
// 		$contentType = mime_content_type($attachment);
// 		$payload     = file_get_contents($attachment); 
// 		$file =  pathinfo($attachment);
// 		$name     =  time().".".$file['extension']; 
		

		
// 	//	upload file by html or php code 
//         /* $finfo       = finfo_open ( FILEINFO_MIME_TYPE );
// 		$contentType = finfo_file ( $finfo , $attachment['tmp_name']  );
// 		$payload     = file_get_contents ( $attachment['tmp_name']  ); */ 

// 		curl_setopt ( $this->_curl_initialization , CURLOPT_URL , sprintf ( $this->server_api_url . '/%s/%s/%s?rev=%s' , $database , $documentID , $name , $revision ) );
// 		curl_setopt ( $this->_curl_initialization , CURLOPT_CUSTOMREQUEST , 'PUT' );
// 		//curl_setopt( $this->_curl_initialization, CURLOPT_POST, true);
// 		curl_setopt ( $this->_curl_initialization , CURLOPT_POSTFIELDS , $payload );
// 		curl_setopt ( $this->_curl_initialization , CURLOPT_RETURNTRANSFER , TRUE );
// 		curl_setopt (
// 			$this->_curl_initialization , CURLOPT_HTTPHEADER , array (
// 				                            'Content-type: ' . $contentType ,
// 				                            'Accept: */*' ,
// 			                            )
// 		);
// 		if ( $this->_my_db_password && $this->_my_db_username )
// 		{
// 			curl_setopt ( $this->_curl_initialization , CURLOPT_USERPWD , $this->_my_db_username . ':' . $this->_my_db_password );
// 		}
// 		$response = curl_exec ( $this->_curl_initialization );
// 		return $response;
// 	}
// 	/**
// 	 * Get a document
// 	 *
// 	 *
// 	 * To retrieve a document, simply perform a GET operation at the document’s URL like shown below. Here we are using
// 	 * $documentID = ‘ajzele’; to fetch the document we just created in previous example.
// 	 *
// 	 * @param $database
// 	 * @param $documentID
// 	 *
// 	 * @return mixed
// 	 */
// 	public function getDocument ( $database , $documentID )
// 	{
// 		curl_setopt ( $this->_curl_initialization , CURLOPT_URL , $this->server_api_url . '/' . $database . '/' . $documentID );
// 		curl_setopt ( $this->_curl_initialization , CURLOPT_CUSTOMREQUEST , 'GET' );
// 		curl_setopt ( $this->_curl_initialization , CURLOPT_RETURNTRANSFER , TRUE );
// 		curl_setopt (
// 			$this->_curl_initialization , CURLOPT_HTTPHEADER , array (
// 				                            'Content-type: application/json' ,
// 				                            'Accept: */*' ,
// 			                            )
// 		);
// 		if ( $this->_my_db_password && $this->_my_db_username )
// 		{
// 			curl_setopt ( $this->_curl_initialization , CURLOPT_USERPWD , $this->_my_db_username . ':' . $this->_my_db_password );
// 		}
// 		$response = curl_exec ( $this->_curl_initialization );
// 		return $response;
// 	}
// 	/**
// 	 *
// 	 * Update existing document
// 	 *
// 	 * Note the _rev value $document_rev (1-c3fde3a56fe3c3490448a8e34166b4ec) in Create a document example. Prefix 1
// 	 * means first revision. If you try to execute the same create a document request multiple times, you would get
// 	 * Document update conflict. error message. This is because CouchDB sees your request as update. If you want to
// 	 * update or delete a document, CouchDB expects you to include the _rev field of the revision you wish to change.
// 	 * When CouchDB accepts the change, it will generate a new revision number.
// 	 *
// 	 * In order to update our previously create document, we need to pass it the _rev number we got from CouchDB when
// 	 * we created the document. Below is an example of such request, note how it is almost identical.
// 	 *
// 	 * @param array $document_data
// 	 * @param       $database
// 	 * @param       $document
// 	 * @param bool  $document_rev
// 	 *
// 	 * @return mixed
// 	 */
// 	public function updateDocument ( $document_data = array () , $database , $document , $document_rev = FALSE )
// 	{
// 		if ( $document_rev )
// 		{
// 			$document_data[ '_rev' ] = $document_rev;
// 		}
// 		curl_setopt ( $this->_curl_initialization , CURLOPT_URL , $this->server_api_url . '/' . $database . '/' . $document );
// 		curl_setopt ( $this->_curl_initialization , CURLOPT_CUSTOMREQUEST , 'PUT' ); /* or PUT */
// 		curl_setopt ( $this->_curl_initialization , CURLOPT_POSTFIELDS , json_encode ( $document_data ) );
// 		curl_setopt ( $this->_curl_initialization , CURLOPT_RETURNTRANSFER , TRUE );
// 		curl_setopt (
// 			$this->_curl_initialization , CURLOPT_HTTPHEADER , array (
// 				                            'Content-type: application/json' ,
// 				                            'Accept: */*' ,
// 			                            )
// 		);
// 		if ( $this->_my_db_password && $this->_my_db_username )
// 		{
// 			curl_setopt ( $this->_curl_initialization , CURLOPT_USERPWD , $this->_my_db_username . ':' . $this->_my_db_password );
// 		}
// 		$response = curl_exec ( $this->_curl_initialization );
// 		return $response;
// 	}
// 	/**
// 	 * Delete a document
// 	 *
// 	 * To delete a document you need to perform a HTTP DELETE operation at the document’s location, passing the rev
// 	 * parameter with the document’s current revision.
// 	 *
// 	 * @param $database
// 	 * @param $documentID
// 	 * @param $revision
// 	 *
// 	 * @return mixed
// 	 */
// 	public function deleteDocument ( $database , $documentID , $revision )
// 	{
// 		curl_setopt ( $this->_curl_initialization , CURLOPT_URL , sprintf ( $this->_my_db_username . '/%s/%s?rev=%s' , $database , $documentID , $revision ) );
// 		curl_setopt ( $this->_curl_initialization , CURLOPT_CUSTOMREQUEST , 'DELETE' );
// 		curl_setopt ( $this->_curl_initialization , CURLOPT_RETURNTRANSFER , TRUE );
// 		curl_setopt (
// 			$this->_curl_initialization , CURLOPT_HTTPHEADER , array (
// 				                            'Content-type: application/json' ,
// 				                            'Accept: */*' ,
// 			                            )
// 		);
// 		if ( $this->_my_db_password && $this->_my_db_username )
// 		{
// 			curl_setopt ( $this->_curl_initialization , CURLOPT_USERPWD , $this->_my_db_username . ':' . $this->_my_db_password );
// 		}
// 		$response = curl_exec ( $this->_curl_initialization );
// 		return $response;
// 	}
// }