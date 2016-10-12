<?
$PDO = new PDO( 'mysql:host=localhost;dbname=bd_sisqrcode', 'root', 'graomestre10' );

$requestedTimestamp = isset ( $_GET [ 'timestamp' ] ) ? (int)$_GET [ 'timestamp' ] : date("Y-m-d H:i:s",time());

while ( true )
{
    // echo time();
    $stmt = $PDO->prepare( "SELECT * FROM participante_has_palestra INNER JOIN participante ON participante_has_palestra.participante_id = participante.id WHERE participante_has_palestra.data_criado > :requestedTimestamp" );
    $stmt->bindParam( ':requestedTimestamp', $requestedTimestamp );
    $stmt->execute();
    $rows = $stmt->fetchAll( PDO::FETCH_ASSOC );

    if ( count( $rows ) > 0 )
    {
        $json = json_encode( $rows );
        echo $json;
        break;
    }
    else
    {
        sleep( 2 );
        continue;
    }
}
?>
