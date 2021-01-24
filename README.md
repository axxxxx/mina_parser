# mina_parser


The parser was written in haste at 3 am in 2 hours. Please do not judge strictly. The code is not optimal, but working. He fulfilled the set function. If necessary, you can collect additional information, transaction IDs, discord names and other information available on the page with a list of transactions on the donor's website.

The parser is a page controller for CMS Yii2.

Update: v.2.0
* transactions are excluded when the recipient's address is equal to the sender's address (to itself)
* excluded addresses in which the amount received is equal to the amount sent (got rid of the received tokens)
* added statistics on the number of transactions
* added statistics on senders and recipients