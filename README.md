The main use case of this repository is to see code examples as you read through the article, but if you decided to run it locally, do the following:
```
make start
make vendor_courier vendor_customer vendor_restaurant
make db_courier db_customer db_restaurant
make courier_migration customer_migration restaurant_migration
make fixture
```