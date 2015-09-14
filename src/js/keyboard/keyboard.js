require(['jquery', 
        'node_modules/virtual-keyboard/dist/js/jquery.keyboard.min',
        'node_modules/virtual-keyboard/dist/js/jquery.keyboard.extension-typing.min'
        ], function($){
    $.keyboard.layouts.MUFI = 
    {
			'normal': [
				'` 1 2 3 4 5 6 7 8 9 0 - = {bksp}',
				'{tab} a b d e c f g h i j [ ] \\',
				'k l m n o p q r s ; \' {enter}',
				'{shift} t u v w x y z , . / {shift}',
				'{accept} {space} {cancel}'
			],
			'shift': [
				'~ ! @ # $ % ^ & * ( ) _ + {bksp}',
				'{tab} A B C D E F G H I J { } |',
				'K L M N O P Q R S : " {enter}',
				'{shift} T U V W X Y Z < > ? {shift}',
				'{accept} {space} {cancel}'
			]
    };
    return(function(keyboard_id){
        $('keyboard_id')
	        .keyboard({ layout: 'MUFI' })
	        .addTyping();
    });

});
