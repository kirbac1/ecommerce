## Features
The whole application is based on Laravel, and is easily extensible, making it possible to extend some or many of the features developed.

### Models
The models in use are the following:

* User
  + Contact
    - Address
    - Email
    - Phone
    - Website <br><br>
    - Alert
    - Category
    - Discount
    - Item
    - Pricelist
    - Stock
    - Warehouse <br><br>
    - Bundle
    - Product
    - Invoice
    - Order
    - Proforma
* Currency

### Model Description

#### User

The user model is the entity that allows to login into the server.
Each user have at least one contact, that is the person/company who subscribed to the service.

#### Contact
The contact model is the main entity of the CRM. Every information will belong to a contact, whom the user, or a different contact can own.
Whenever you are creating a new employee, for example, you are owning that contact. The same applies for almost every entity of the application.
Moreover, the contacts are the creators and recipients of orders, proformas and invoices to whom they can be sent and/or shared with.

#### Address, Email, Phone and Website
These entities, owned by a contact, are a one-to-many entity, so every contact can have many of these.

#### Alert
The alerts will be executed whenever a trigger gets evaluated as `true`; the triggers can be:

|  Trigger    |  Description                                     |
| ----------- | ------------------------------------------------ |
| min_stock   | The minimum stock for the item has been reached. |
| expired     | The discount code has expired.                   |

When the triggers become true, an email to the contact owning the alert is sent.

#### Category
The category is an entity the items (bundles or products) belong to.

#### Discount
The discount is applied to an item (bundles or products), and can be percent-based or amount-based.

#### Item
The item is an abstraction upon the bundles and the products: each one of them is an item, and this simplifies the management of such entities.

#### Bundle
The bundle is a group of products, sold with the idea of a wholesale.
They can have one or many items belonging to them, with a different price and/or quantity than the ones associated with the single item.

#### Pricelist
The pricelist contains the prices of an item (so, bundles and products). The pricelist can be linked to a particular warehouse, or global.

#### Warehouse
It is where the items are stocked. Can be even associated with an address, and can have contacts working inside it.

#### Stock
The stock is the quantity of the particular item stocked inside a warehouse. An item can have different stocks in different warehouses, and/or alerts linked to each of the stocks.

#### Proforma
The proforma allows users to give an estimate of the prices contained inside the invoice. Here are the statuses allowed:

|  Status     |  Description                                                |
| ----------- |------------------------------------------------------------ |
| pending     | The proforma has been issued, but not accepted.             |
| accepted    | The proforma has been agreed by both parts.                 |
| cancelled   | The proforma has been cancelled.                            |

#### Order
The order is issued *after* the proforma has been accepted by both the parts, and starts with the *pending* state. Once the order is shipped, it will be in *shipped* state, and upon completion will be in *completed* state. Here follow the statuses allowed:

|  Status     |  Description                                                |
| ------------| ----------------------------------------------------------- |
| pending     | The order is pending.                                       |
| completed   | The order is complete.                                      |
| shipped     | The order has been shipped.                                 |
| cancelled   | The order has been cancelled.                               |

#### Invoices

Upon completion of the order, and after the sum has been paid, invoices can be generated.

|  Status     |  Description                                                |
| ------------| ----------------------------------------------------------- |
| pending     | The invoice is awaiting to be issued.                       |
| issued      | The invoice has been issued. Everything has been completed. |
| refunding   | The invoice is going to be refunded.                        |
| refunded    | The invoice has been cancelled, and the sum refunded.       |


### APIs

RESTful APIs are already included, and are available at: `/api/v1/`.
The following endpoints have been created:

```
/api/v1/users
/api/v1/users/{user_id}/contacts

/api/v1/contacts
/api/v1/contacts/{contact_id}
/api/v1/contacts/{contact_id}/addresses/{address_id}
/api/v1/contacts/{contact_id}/alerts/{alert_id}
/api/v1/contacts/{contact_id}/bundles/{bundle_id}
/api/v1/contacts/{contact_id}/categories/{category_id}
/api/v1/contacts/{contact_id}/contacts/{contact_id}
/api/v1/contacts/{contact_id}/discounts/{discount_id}
/api/v1/contacts/{contact_id}/emails/{email_id}
/api/v1/contacts/{contact_id}/items/{item_id}
/api/v1/contacts/{contact_id}/phones/{phone_id}
/api/v1/contacts/{contact_id}/pricelists/{pricelist_id}
/api/v1/contacts/{contact_id}/products/{product_id}
/api/v1/contacts/{contact_id}/stocks/{stock_id}
/api/v1/contacts/{contact_id}/warehouses/{warehouse_id}
/api/v1/contacts/{contact_id}/websites/{website_id}

/api/v1/orders
/api/v1/orders/{order_id}
/api/v1/proformas
/api/v1/proformas/{proforma_id}
/api/v1/invoices
/api/v1/invoices/{invoice_id}

/api/v1/currencies
/api/v1/currencies/{currency_id}
```

Whenever you try to access to the data stored by a user that is not yours, you can access the data that relates your contacts.

Ex.: You are the contact called "John Doe", and have been billed by "ACME Ltd".
You can find the "ACME Ltd" this way using the endpoint `/api/v1/contacts/` and getting a list of all your contacts.

Now, let's say that "ACME Ltd" is the contact \#123.
You can then access the invoices generated by the "ACME Ltd" using this endpoint: `/api/v1/contacts/123/invoices`.

This strategy allows you to get all the data pertinent to your user (and, of course, your contacts) using RESTful endpoints.

### Creating, editing and deleting data
You have full read/write access to the data of your contacts, and read-only access to the data you are involved with, but you (or your contacts) did not generate.

The endpoints in this case, follow the RESTful standards:

|  Method   |  Endpoint                     |  Description                                                             |
| --------- | ----------------------------- | ------------------------------------------------------------------------ |
| GET       | `/products`                   | Retrieves a list of all the products.                                    |
| GET       | `/products/create`            | Retrieves the HTML page to create a new product.                         |
| POST      | `/products`                   | Saves a new product.                                                     |
| GET       | `/products/{product_id}`      | Retrieves the product whose id is `{product_id}`.                        |
| GET       | `/products/{product_id}/edit` | Retrieves the HTML page to edit the product with id `{product_id}`.      |
| PUT/PATCH | `/products/{product_id}`      | Updates the product with id `{product_id}`.                              |
| DELETE    | `/products/{product_id}`      | Deletes the product with id `{product_id}`.                              |
