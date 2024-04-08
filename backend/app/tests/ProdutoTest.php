<?php
use PHPUnit\Framework\TestCase;
use App\Controller\ProdutoController;
use App\Persistence\ConnectionCreator;
use App\Http\Request;

class ProdutoTest extends TestCase {
    private $pdo;
    private $request;

    protected function setUp(): void {
        $this->pdo = $this->createMock(\PDO::class);

        if (!function_exists('getallheaders')) {
            function getallheaders() {
                return [
                    // Retorne um array associativo com cabeçalhos fictícios para seus testes
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer token_ficticio'
                ];
            }
        }

        parent::setUp();
        $_SERVER['REQUEST_METHOD'] = 'POST';

        $this->request = $this->createMock(Request::class);

        ConnectionCreator::createConnection($this->pdo);
    }

    public function testAddSuccess() {
        $body = ['descricao' => 'Produto Teste', 'codigo_tipo' => 1, 'valor' => 10.50];
        $this->request->method('getBody')->willReturn($body);

        $stmt = $this->createMock(\PDOStatement::class);
        $stmt->method('execute')->willReturn(true);
        $stmt->method('bindValue')->withConsecutive(
            [$this->equalTo('descricao'), $this->equalTo($body['descricao'])],
            [$this->equalTo('codigo_tipo'), $this->equalTo($body['codigo_tipo'])],
            [$this->equalTo('valor'), $this->equalTo($body['valor'])]
        );
        $this->pdo->method('prepare')->willReturn($stmt);

        $result = ProdutoController::add();

        $this->assertGreaterThan(0, $value, "O valor deve ser maior que 0.");
    }

    public function testAddFailure() {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Informe parâmetros válidos');

        $body = ['descricao' => '', 'codigo_tipo' => null, 'valor' => null];
        $this->request->method('getBody')->willReturn($body);

        ProdutoController::add();
    }
}
