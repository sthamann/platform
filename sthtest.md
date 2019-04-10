? Database Schema

Primary Key
Product Key
Context Key
UserId Key
shopId Key

UniqueIndex - Title + Content + Stars
--
Rating Date
Rating externalUser - Konfigurierbar
Rating externalEmail
Rating Labels / Origin / Source - Klassifikation
Rating Language - "de"
Rating title
Rating content
Rating positive - Increment
Rating negative - Increment
Rating points = 0.0 - 5.0
Rating Status = Approved 1, Declined 0
Rating Comment
Rating CommentDate
--

product_rating

id
product_id
customer_id NULL
external_user
external_email
origin
sales_channel_id
language_id
title
content
positive
negative
points
status
comment
comment_created_at
created_at
updated_at

