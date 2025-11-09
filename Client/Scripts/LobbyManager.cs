using System.Collections;
using UnityEngine;
using UnityEngine.Networking;
using UnityEngine.UI;
using UnityEngine.SceneManagement;

public class LobbyManager : MonoBehaviour
{
    public InputField matchname;
    public Text log;
    public void JoinMatch()
    {
        if (matchname.text.Trim() == "")
        {
            log.text = "Введите название матча";
            return;
        }
        StartCoroutine(JoinMatchRequest());
    }
    public void CreateMatch()
    {
        if (matchname.text.Trim() == "")
        {
            log.text = "Введите название матча";
            return;
        }
        StartCoroutine(CreateMatchRequest());
    }
    IEnumerator JoinMatchRequest()
    {
        UnityWebRequest request = UnityWebRequest.Get($"{Config.server}/EnterToMatch.php?matchname={matchname.text.Trim()}");
        yield return request.SendWebRequest();
        if(request.error != null)
        {
            log.text = "Ошибка подключения к серверу";
            yield break;
        }
        bool errorli = false;
        switch (request.downloadHandler.text)
        {
            case "Game ended":
                log.text = "Матч окончен";
                errorli = true;
                break;
            case "The match is overcrowded":
                log.text = "Матч переполнен";
                errorli = true;
                break;
            case "Match not found":
                log.text = "Матч не найден";
                errorli = true;
                break;
            default:
                break;
        }
        if (errorli) yield break;
        ClientSide.role = "x";
        ClientSide.id = request.downloadHandler.text;
        ClientSide.matchnamed = matchname.text.Trim();
        SceneManager.LoadScene("Game");
    }
    IEnumerator CreateMatchRequest()
    {
        UnityWebRequest request = UnityWebRequest.Get($"{Config.server}/CreateMatch.php?matchname={matchname.text.Trim()}");
        yield return request.SendWebRequest();
        if (request.error != null)
        {
            log.text = "Ошибка подключения к серверу";
            yield break;
        }
        switch (request.downloadHandler.text)
        {
            case "You have not entered a match name!":
                log.text = "Введите название матча";
                break;
            case "This match already exists":
                log.text = "Данный матч уже создан";
                break;
            case "The match was successfully created":
                StartCoroutine(JoinMatchRequest());
                break;
            default:
                break;
        }
    }
}
