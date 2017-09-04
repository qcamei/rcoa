<?php
/**
 *  wechat 为企业微信配置文件 以下为模板
 * 'wechat' => [
 *       'CorpId' => 'wwd0cb15376ce0a58a',----在管理后台“我的企业”－“企业信息”下查看
 *       'TxlSecret' => 'T69ShUuyVTBbLkNEcUxCV6bVo7jBYbvM2eBt_K1HFT0',----通讯录接口的密钥在“管理工具”-“通讯录同步”里面查看
 *       'AppsConfig' => [              ----自定义应用
 *           'AppDesc' => '消息通知',    ----应用1的描述
 *           'AgentId' => 1000007,      ----应用1的Id
 *           'Secret' => 'tva4zdWy3WR1UmiHONFdOi05WXqTDvcKum6etnNtNRA',----应用1的密钥，在管理后台查看
 *       ]
 *   ]
 */
return [
    'adminEmail' => 'admin@example.com',
    'supportEmail' => 'support@example.com',
    'user.passwordResetTokenExpire' => 3600,
    'wechat' => [
        'CorpId' => 'wwd0cb15376ce0a58a',
        'TxlSecret' => 'T69ShUuyVTBbLkNEcUxCV6bVo7jBYbvM2eBt_K1HFT0',
        'AppsConfig' => [
            'AppDesc' => '消息通知',
            'AgentId' => 1000007,
            'Secret' => 'tva4zdWy3WR1UmiHONFdOi05WXqTDvcKum6etnNtNRA',
        ]
    ]
];
