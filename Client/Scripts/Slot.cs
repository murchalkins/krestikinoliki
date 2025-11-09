using UnityEngine;
using UnityEngine.UI;
using System.Collections;
using UnityEngine.SceneManagement;
using UnityEngine.Networking;

public class Slot : MonoBehaviour
{
    public byte slotname;
    public void ApplyValue(string wordik)
    {
        if(transform.GetChild(0).GetComponent<Text>().text == "")
        {
            StartCoroutine(PushWord());
        }
    }
    IEnumerator PushWord()
    {  
        UnityWebRequest request = UnityWebRequest.Get($"{Config.server}/ActionPlayer.php?matchname={ClientSide.matchnamed}&id={ClientSide.id}&number={slotname}");
        yield return request.SendWebRequest();
        if(request.error != null)
        {
            Debug.LogError("Error Set Number Data");
            StartCoroutine(PushWord());
            yield break;
        }
        Text log = GameObject.FindWithTag("log").GetComponent<Text>();
        switch (request.downloadHandler.text)
        {
            case "Wrong number":
                log.text = "Неправильная цифра";
                break;
            case "Game ended":
                log.text = "Матч не окончен";
                break;
            case "Game not started":
                log.text = "Матч не запущен";
                break;
            case "It's not your queue":
                log.text = "Сейчас не твоя очередь";
                break;
            case "The slot is occupied":
                log.text = "Слот уже занят";
                break;
            case "The match does not exist":
                log.text = "Матча не существует";
                break;
            default:
                break;
        } 
        Invoke("DestroyMsg", 1);
    } 
    void DestroyMsg()
    {
     GameObject.FindWithTag("log").GetComponent<Text>().text = "";
    }
}
