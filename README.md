# emq(Event Message Queue)



### 示例
```
# kafka消费示例
php examples/consumer/kafkaConsumer.php


# 创建一个主题
docker exec -i docker_kafka_1 /opt/kafka_2.11-2.0.1/bin/kafka-topics.sh --create --zookeeper 199.199.199.199:2181 --replication-factor 1 --partitions 6 --topic test



# 运行一个消息生产者，指定topic为刚刚创建的主题
docker exec -i docker_kafka_1 /opt/kafka_2.11-2.0.1/bin/kafka-console-producer.sh --broker-list 199.199.199.199:9092 --topic test


docker exec -i docker_kafka_1 /opt/kafka_2.11-2.0.1/bin/kafka-console-producer.sh --broker-list 199.199.199.199:9092 --topic test



{"id" : "20181016150302-0.05498700-182.150.27.74-8760-1","eventKey" : "TEST","data" : {"app_id" : 14,"app_name" : "hdsq","company_id" : 0,"corpid" : "wweace8ae2c27a051f","init_event_name" : 1,"create_uid" : 0,"create_time" : "2018-10-16 15 : 03 : 02"},"time" : 1539673382,"ip" : "182.150.27.74"}

{"id" : "20181016150302-0.05498700-182.150.27.74-8760-1","eventKey" : "TEST_OK","data" : {"app_id" : 14,"app_name" : "hdsq","company_id" : 0,"corpid" : "wweace8ae2c27a051f","init_event_name" : 1,"create_uid" : 0,"create_time" : "2018-10-16 15 : 03 : 02"},"time" : 1539673382,"ip" : "182.150.27.74"}
```
