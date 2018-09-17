# image

图片水印处理，快速添加水印，无需扩展包

## 安装

```shell
$ composer require itdream/image -vvv
```

## 使用

```shell
use Itdream\Image\Image;

$image = new Image(
        '背景图片绝对路径', 
        ‘保存图片绝对路径’, 
        'JPG'  // 可选参数 GIF JPG PNG
    );

// 添加文字水印
echo $w->word(
        '恭喜1212121！',  // 文字
        '/fonts/msyh.ttf',  // 字体
        '28',  // 字体大小
        ['51','51','51'],  // RGB颜色
        ['100','288'],  // 文字相对左上角 起始位置
        'center'  // 居中设置
        );
// 添加图片水印
echo $w->img(
        'logo.png',  // 水印图片
        ['10', '10'], //  图片相对左上角 起始位置
        'center',  // 居中设置
    );
```

## License

MIT