# 所见即所得编辑器的使用说明

- 不要手动拷贝到 public 目录, 通过 yarn 或者 npm 来管理
- 如何配合 VueJS 来使用

```javascript
    // 由于在框架中没有直接的引入 VueJs, 所以必须在 document 已经 ready 的情况下, 才能使用
    $(document).ready(function(){
        new window.Vue({    // 因为没有全局的 Vue 实例, 因此需要通过 window.Vue 来引用即可
            el: '#enrol-note-manager-app',
            data() {
                return {
                    content: '<h1>HTML 的文本内容</h1>',
                    configOptions: {
                        lang:'zh_cn', // 语言使用中文
                        plugins: [    // 所要加载的插件
                           'fontsize',
                           'fontcolor',
                           'alignment',
                           'fontfamily',
                           'table',
                           'specialchars',
                           'imagemanager',
                           'filemanager',
                       ],
                       fileUpload: '/your-upload-script/',  // 文件上传的 Action
                       fileManagerJson: '/your-folder/files.json', // 已存在的文件的资源 URL, 返回为 json 格式
                       imageUpload: '/your-upload-script/', // 图片上传的 Action
                       imageManagerJson: '/your-folder/images.json', // 已存在的图片的资源 URL, 返回为 json 格式
                    }
                }
            }
        });
    });
```

- 已存在的图片的资源 URL, 返回的 json 格式数据样本

```json
[
    {
        "title": "File 1",
        "name": "1.txt",
        "url": "/file-url/1.txt",
        "id": 1, 
        "size": "301Kb"
    },
    {
        "title": "File 2",
        "name": "2.txt",
        "url": "/file-url/2.txt",
        "id": 2,
        "size": "1MB"
    }
]
```

- 已存在的图片的资源 URL, 返回的 json 格式数据样本
```json
[
    {
        "thumb": "/image-thumbnail-url/1.jpg",
        "url": "/image-url/1.jpg",
        "id": "img1",
        "title": "Image 1"
    },
    {
        "thumb": "/image-thumbnail-url/2.jpg",
        "url": "/image-url/2.jpg",
        "id": "img2",
        "title": "Image 2"
    }
]
```

## 只使用纯的 redactor 编辑器

```php
/**
 * 在你的控制器方法中
 */
public function your_action_in_controller(Request $request){
    $this->dataForView['redactor'] = true; // 让框架帮助你自动插入导入 redactor 的 css 和 js 语句   
    
    // 将所需要的 js 代码加入到页面中
    $this->dataForView['js'] = [
        'school_manager.recruitStudent.consult.note_js'
    ];
}
```


## 以 Vue 组件的方式使用 redactor 编辑器

```php
/**
 * 在你的控制器方法中
 */
public function your_action_in_controller(Request $request){
    $this->dataForView['redactor'] = true;          // 让框架帮助你自动插入导入 redactor 的 css 和 js 语句   
    $this->dataForView['redactorWithVueJs'] = true; // 让框架帮助你自动插入导入 redactor 的组件的语句
    
    // 将所需要的 js 代码加入到页面中
    $this->dataForView['js'] = [
        'school_manager.recruitStudent.consult.note_js'
    ];
}
```

