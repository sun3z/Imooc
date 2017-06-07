## 慕课网视频下载爬虫

爬取网页版... 非调用之前的大部分视频失效接口，清晰度一般但可下载。

## 如何使用

```
# 下载项目
git clone https://github.com/sun3z/Imooc.git

# 安装依赖
composer install

# 开始使用...

```



### 暂支持两种方式下载

1. 使用命令行工具查询慕课网分类，选择课程进行下载。
2. 已知课程id，直接输入课程id进行下载。



#### 方式一：输入分类

可以输入慕课网课程列表上的方向，如 fe be data

可以输入慕课网课程列表上的分类，如 html css3 php

``` 
php run.php fe
```



#### 方式二：输入课程id（新增）

http://www.imooc.com/learn/834

```
php run.php 834
```

#### 图不补了，还是那么回事..

![](img/run.png)

开始下载

![](img/download.png)

下载完成~

![](img/videos.png)

