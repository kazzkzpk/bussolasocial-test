
### Como instalar o projeto

É necessário ter PHP 8.3 e composer, ou Docker.

Instale as dependências do projeto a partir do Docker:

```docker-compose up -d --build```

```docker exec -it php bash```

```composer install```

Instale as dependências do projeto nativamente:

```composer install```

### Detalhes importantes
- Existem verificações específicas, como: items removidos do catálogo são verificados e fazer throw de exception caso encontrados em um carrinho de compras, e, valores divergentes do item no carrinho com o catálogo também fazer throw de exception. 
- Como padrão, validators devem ser feitos utilizando os requests dos controllers. Com exceção de 2 cenários: o payload a ser utilizado veio através de uma fila sem validação (ex: webhook de API externa integrada) ou a partir de WebSocket e montado no frontend. Como o teste foi feito sem API, conforme pedido, optei pela validação no serviço, imaginando que seja um dos dois cenários acima.
- Testes Unitários costumam ser mockados para simular filas e acesso a repositórios do banco de dados. Como não é o caso, os testes não foram mockados.

## Classes/serviços

### Catalog
Responsável pelo cadastro de items.

É um Singleton, portanto, utiliza ServiceProvider.

Utilizado para validação de preço e se o item ainda existe antes de fechar o carrinho de compras.

### Catalog/Item
É um item (produto) que poderá ser adicionado no catálogo ou carrinho de comprar.

### Credit Card
Responsável pelo cadastro de cartão de crédito.

### Shopping Cart
Responsável pelo carrinho de compras.

### Payment/Pix
Responsável por fechar o pedido com a forma de pagamento PIX.

Inclui algorítimo para descontos.

### Payment/CreditCard
Responsável por fechar o pedido com a forma de pagamento PIX.

Inclui algorítimo para descontos e taxas, conforme parcelamento.

## Simulando um pedido

Se estiver utilizando docker, abrir a bash:
```docker exec -it php bash```

#### Para simular um pedido efetuado com PIX e 3 diferentes produtos:

```php artisan app:simulate-shopping-cart:request-pix```

Output esperado:

```
Initialize Simulate Shopping Cart with PIX Request
Adding 2x item id 1 [Smart Tv Samsung 32" Polegadas Led Hd Wi-fi Hdmi] with unit cost [R$ 1.047,88] to item cart
Shopping Card total value: R$ 2.095,76
Adding 2x item id 1 [Cooktop Gás Dako Supreme Vidro Temperado 220V Preto] with unit cost [R$ 327,08] to item cart
Shopping Card total value: R$ 2.749,92
Adding 1x item id 1 [Geladeira Brastemp Frost Free 375L 220V] with unit cost [R$ 3.209,90] to item cart
Shopping Card total value: R$ 5.959,82
Pix Payment total value: R$ 5.363,84
Pix Payment discount value: R$ 595,98
```

#### Para simular um pedido efetuado com cartão de crédito parcelado 1x e 2 diferentes produtos:

```php artisan app:simulate-shopping-cart:request-creditcard-1x```

Output esperado:

```
Initialize Simulate Shopping Cart with Credit Card (1x installments) Request
Adding 2x item id 1 [Smart Tv Samsung 32" Polegadas Led Hd Wi-fi Hdmi] with unit cost [R$ 1.047,88] to item cart
Shopping Card total value: R$ 2.095,76
Adding 2x item id 1 [Cooktop Gás Dako Supreme Vidro Temperado 220V Preto] with unit cost [R$ 327,08] to item cart
Shopping Card total value: R$ 2.749,92
Credit Card Payment total value: R$ 2.474,93
Credit Card Payment discount value: R$ 274,99
Credit Card Payment fees value: R$ 0,00
Request with 1x installments at value: R$ 2.474,93
```

#### Para simular um pedido efetuado com cartão de crédito parcelado 6x e 2 diferentes produtos:

```php artisan app:simulate-shopping-cart:request-creditcard-6x```

Output esperado:
```
Initialize Simulate Shopping Cart with Credit Card (6x installments) Request
Adding 2x item id 1 [Smart Tv Samsung 32" Polegadas Led Hd Wi-fi Hdmi] with unit cost [R$ 1.047,88] to item cart
Shopping Card total value: R$ 2.095,76
Adding 1x item id 1 [Geladeira Brastemp Frost Free 375L 220V] with unit cost [R$ 3.209,90] to item cart
Shopping Card total value: R$ 5.305,66
Credit Card Payment total value: R$ 5.632,06
Credit Card Payment discount value: R$ 0,00
Credit Card Payment fees value: R$ 326,40
Request with 1x installments at value: R$ 938,71
Request with 5x installments at value: R$ 938,67
```

### TODO:
- Docker
- Testes Unitários
