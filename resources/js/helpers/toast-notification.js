import { useToast } from "vue-toast-notification";
const toast = useToast()

export function showError(message){
    toast.error(message,{
        position:'bottom-right',
        duration:3000,
        dismissible:true
    })
}
export function successMsg(message){
    toast.success(message,{
        position:'bottom-left',
        duration:3000,
        dismissible:true
    })
}