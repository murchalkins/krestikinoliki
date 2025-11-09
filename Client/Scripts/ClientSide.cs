using System.Collections;
using System.Collections.Generic;
using UnityEngine;
using UnityEngine.Networking;
using UnityEngine.UI;
using Newtonsoft.Json;
using UnityEngine.SceneManagement;

public class ClientSide : MonoBehaviour
{
    public static string id = "";
    public static string matchnamed = "";
    public static string role = "";
    private float timer;
    private bool used;
    void Update()
    {
        if (timer <= 0f)
        {
            StartCoroutine(GetDatas());
            timer = 1f;
        }
        else
        {
            timer -= Time.deltaTime;
        }
    }
    IEnumerator GetDatas()
    {
        Text log = GameObject.FindWithTag("log").GetComponent<Text>();
        UnityWebRequest request = UnityWebRequest.Get($"{Config.server}/matches/{matchnamed}.txt");
        yield return request.SendWebRequest();
        if(request.error != null)
        {
            Debug.LogError("Error Get Data");
            yield break;
        }
        Dictionary<string, string> arraydata = JsonConvert.DeserializeObject<Dictionary<string, string>>(request.downloadHandler.text);
        if (arraydata["started"] == "false" && arraydata["ended"] == "false")
        {
            role = "o";
            log.text = "Ожидание игроков";
            GameObject.FindWithTag("role").GetComponent<Text>().text = $"Ваша буква: {role}";
            yield break;
        }
        GameObject.FindWithTag("role").GetComponent<Text>().text = $"Ваша буква: {role}";
        if (arraydata["started"] == "true")
        {
            if(!used)
            {
            log.text = "Игра началась";
            Invoke("DestroyMsg", 1);
            used = true;
            yield break;
            }
        }
        if(arraydata["started"] == "false" && arraydata["ended"] == "true")
        {
            if(arraydata["winner"] == id)
            {
                if(role == "o")
                {
                    log.text = "Победила буква O";
                }
                else
                {
                   log.text = "Победила буква X";
                }
            }
            if(arraydata["winner"] != id && arraydata["winner"] != "")
            {
                if(role == "o")
                {
                    log.text = "Победила буква X";
                }
                else
                {
                   log.text = "Победила буква O";
                }
            }
            if(arraydata["winner"] == "")
            {
               log.text = "Ничья";
            }
        }
        GameObject[] slots = GameObject.FindGameObjectsWithTag("slots");
        foreach(GameObject slotik in slots)
        {
            if (slotik.GetComponent<Slot>().slotname == 1)
            {
                slotik.transform.GetChild(0).GetComponent<Text>().text = arraydata["1"];
            }
            if (slotik.GetComponent<Slot>().slotname == 2)
            {
                slotik.transform.GetChild(0).GetComponent<Text>().text = arraydata["2"];
            }
            if (slotik.GetComponent<Slot>().slotname == 3)
            {
                slotik.transform.GetChild(0).GetComponent<Text>().text = arraydata["3"];
            }
            if (slotik.GetComponent<Slot>().slotname == 4)
            {
                slotik.transform.GetChild(0).GetComponent<Text>().text = arraydata["4"];
            }
            if (slotik.GetComponent<Slot>().slotname == 5)
            {
                slotik.transform.GetChild(0).GetComponent<Text>().text = arraydata["5"];
            }
            if (slotik.GetComponent<Slot>().slotname == 6)
            {
                slotik.transform.GetChild(0).GetComponent<Text>().text = arraydata["6"];
            }
            if (slotik.GetComponent<Slot>().slotname == 7)
            {
                slotik.transform.GetChild(0).GetComponent<Text>().text = arraydata["7"];
            }
            if (slotik.GetComponent<Slot>().slotname == 8)
            {
                slotik.transform.GetChild(0).GetComponent<Text>().text = arraydata["8"];
            }
            if (slotik.GetComponent<Slot>().slotname == 9)
            {
                slotik.transform.GetChild(0).GetComponent<Text>().text = arraydata["9"];
            }
        }
    }
    void DestroyMsg()
    {
      GameObject.FindWithTag("log").GetComponent<Text>().text = "";
    }
    public void LeaveMatch()
    {
        StartCoroutine(LeaveMatchRequest());
    }
    IEnumerator LeaveMatchRequest()
    {
       UnityWebRequest request = UnityWebRequest.Get($"{Config.server}/LeaveMatch.php?matchname={matchnamed}&id={id}");
       yield return request.SendWebRequest();
       if(request.error != null)
       {
            Debug.LogError("Leave Match Error");
            StartCoroutine(LeaveMatchRequest());
            yield break;
       }
       SceneManager.LoadScene("SampleScene");
    }
}
