The main use case of this repository is to see code examples as you read through the article, but if you decided to run it locally, do the following:
```
make start
make vendor_courier && make vendor_customer && make vendor_restaurant
make db_courier && make db_customer && make db_restaurant
make courier_migration && make customer_migration && make restaurant_migration
make fixture
```