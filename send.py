import pika

credentials=pika.PlainCredentials('admin','admin')
connection = pika.BlockingConnection(pika.ConnectionParameters('serverb.example.com',5672,'/',credentials))
channel = connection.channel()
val=input("PLease put message to send ")
channel.queue_declare(queue='hello')
channel.basic_publish(exchange='',
                      routing_key='hello',
                      body=val)
print(" [x] Sent " + val)

connection.close()


