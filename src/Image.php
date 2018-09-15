<?php

namespace Itdream\Image;

class Image
{
    // 背景图片
    protected $bg_img;
    // 输出图片位置
    protected $echo_img_path;
    // 输出图片类型
    protected $dst_type;

    public function __construct($bg_img, $echo_img_path,$dst_type=2)
    {
        // 设置背景图片
        $this->bg_img = $bg_img;
        // 设置生成图片地址
        $this->echo_img_path = $echo_img_path;
        // 设置生成图片的类型
        $this->dst_type = $dst_type;
    }

    /**
     *
     * @param $dst_path  图片路径
     * @param $imgName   保存名字
     * @param string $text  水印文字
     * @param $size    字体大小
     * @param $xy      水印开始x、y坐标
     * @param $color   文字颜色
     * @param string $center  是否居中 如果设置了居中 水印x坐标失效
     */
    public function word($text, $font_path='/fonts/msyh.ttf', $size, $color, $xy, $center='')
    {
        // 背景图片实例
        $dst = $this->isBgImg();
        // 获取背景图片的宽高
        $bgHeightWidth = $this->bgHeightWidth($dst);
        // 设置字体
        $font = __DIR__ .$font_path;
        // 文字水平居中实质
        $fontBox = imagettfbbox($size, 0, $font, $text);
        // 字体颜色
        $fontColor = imagecolorallocate($dst, $color[0], $color[1], $color[2]);  //0x00, 0x00, 0x00
        // 默认开始位置
        $x_start = $xy[0]+1;
        // 判断是否居中
        if($center != ''){
            // 居中  左侧x坐标位置 = (背景图片宽度 - 文字宽度)/2
            $x_start = ceil(($bgHeightWidth['width'] - $fontBox[2]) / 2);
        }
        // 文字水印
        imagefttext($dst, $size, 0, $x_start, $xy[1], $fontColor, $font, $text);
        // 生成图片
        $this->saveImageType($dst);

        return $this->echo_img_path;
    }

    public function img($src_path,$xy=['10','10'],$center='', $dst_type=2)
    {
        //创建图片的实例
        $dst = $this->isBgImg();
        $src = imagecreatefromstring(file_get_contents($src_path));
        // 获取背景图片宽度
        $bg_width = imagesx ( $dst );
        // 获取水印图片宽度
        $sy_width = imagesx ( $src );
        //获取水印图片的宽高
        list($src_w, $src_h) = getimagesize($src_path);

        // 判断是否居中
        if($center != ''){
            // 居中  左侧x坐标位置 = (背景图片宽度 - 水印图片宽度)/2
            $xy[0] = ceil(($bg_width - $sy_width) / 2);
        }

        //将水印图片复制到目标图片上，最后个参数50是设置透明度，这里实现半透明效果
        imagecopymerge($dst, $src, $xy[0], $xy[1], 0, 0, $src_w, $src_h, 100);

        //如果水印图片本身带透明色，则使用imagecopy方法
        // imagecopy($dst, $src, 10, 10, 0, 0, $src_w, $src_h);

        //输出图片
        // list($dst_w, $dst_h, $dst_type) = getimagesize($dst_path);
        $this->saveImageType($dst,$dst_type);

       // imagedestroy($dst);
       // imagedestroy($src);
    }

    // 创建背景图片实例
    private function isBgImg ()
    {
        // 判断背景图片是否存在  当次操作的背景图片
        $dst_path = is_file($this->echo_img_path)?$this->echo_img_path:$this->bg_img;
        // 创建背景图片的实例
        $dst = imagecreatefromstring(file_get_contents($dst_path));

        return $dst;
    }

    // 获取背景图片宽高
    private function bgHeightWidth ($dst)
    {
         // 获取背景图片宽度
        $bg_width = imagesx ( $dst );
        // 背景图片
        $bg_height = imagesy ( $dst );

        return ['height' => $bg_height, 'width' => $bg_width];
    }

    private function saveImageType ($dst)
    {
        // 保存输出图片
        // list($dst_w, $dst_h, $dst_type) = getimagesize($dst_path);
        $echo_img_path = $this->echo_img_path;
        $dst_type = $this->dst_type;
        switch ($dst_type) {
            case 1://GIF
                header('Content-Type: image/gif');
                imagegif($dst,'test.gif');
                break;
            case 2://JPG
                header('Content-Type: image/jpeg');
//                imagejpeg($dst,public_path('test.jpg'));
                imagejpeg($dst,$echo_img_path);
                break;
            case 3://PNG
                header('Content-Type: image/png');
                imagepng($dst,public_path('test.png'));
                break;
            default:
                break;
        }
        imagedestroy($dst);
    }

}