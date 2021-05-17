/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package com.mycompany.Services;

import com.codename1.io.CharArrayReader;
import com.codename1.io.ConnectionRequest;
import com.codename1.io.JSONParser;
import com.codename1.io.NetworkEvent;
import com.codename1.io.NetworkManager;
import com.codename1.ui.events.ActionListener;
import com.mycompany.Models.question;
import com.mycompany.Utils.Statics;
import java.io.IOException;
import java.util.ArrayList;
import java.util.List;
import java.util.Map;

/**
 *
 * @author user
 */
public class question_service {
      public ArrayList<question> questions;
    public static question_service instance = null;
    public boolean resultOK;
    private ConnectionRequest req;
     public question_service() {
        req = new ConnectionRequest();
    }

    public static question_service getInstance() {
        if (instance == null) {
            instance = new question_service();
        }
        return instance;
    }
        public ArrayList<question> parsequestion(String jsonText) {
        try {
            questions = new ArrayList<>();
            JSONParser j = new JSONParser();
            Map<String, Object> ReclamationListJson = j.parseJSON(new CharArrayReader(jsonText.toCharArray()));

            List<Map<String, Object>> list = (List<Map<String, Object>>) ReclamationListJson.get("root");

            for (Map<String, Object> obj : list) {
                question f = new question();

                float id = Float.parseFloat(obj.get("id").toString());
                f.setId((int) id);


                f.setQues(obj.get("question").toString());


             
                questions.add(f);
            }

        } catch (IOException ex) {
            System.out.println("Exception in parsing reclamations ");
        }

        return questions;
    }

    public ArrayList<question> findAll(int id) {
        String url = Statics.BASE_URL + "examen/passer_QCM/"+id;
        req.setUrl(url);
        req.setPost(false);
        req.addResponseListener(new ActionListener<NetworkEvent>() {
            @Override
            public void actionPerformed(NetworkEvent evt) {
                questions = parsequestion(new String(req.getResponseData()));
                req.removeResponseListener(this);
            }
        });
        NetworkManager.getInstance().addToQueueAndWait(req);
        return questions;
    }

}
