
export default {
    vn: {
        attributes: {
            email: 'Email',
            password: 'Mật khẩu'
        },
        messages: {
            required: (field) => `${field} không được để trống.`,
            email: (field) => `${field} không chính xác, phải có dạng abc@example.com`,
        }
    },

    en: {
        attributes: {
            email: 'Email'
        },
        messages: {

        }
    }
}
