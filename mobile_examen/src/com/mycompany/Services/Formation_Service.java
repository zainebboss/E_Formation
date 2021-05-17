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
import com.mycompany.Models.formation;
import com.mycompany.Utils.Statics;
import java.io.IOException;
import java.util.ArrayList;
import java.util.List;
import java.util.Map;

/**
 *
 * @author user
 */
public class Formation_Service {
      public ArrayList<formation> formations;
    public static Formation_Service instance = null;
    public boolean resultOK;
    private ConnectionRequest req;
     public Formation_Service() {
        req = new ConnectionRequest();
    }

    public static Formation_Service getInstance() {
        if (instance == null) {
            instance = new Formation_Service();
        }
        return instance;
    }
        public ArrayList<formation> parseformation(String jsonText) {
        try {
            formations = new ArrayList<>();
            JSONParser j = new JSONParser();
            Map<String, Object> ReclamationListJson = j.parseJSON(new CharArrayReader(jsonText.toCharArray()));

            List<Map<String, Object>> list = (List<Map<String, Object>>) ReclamationListJson.get("root");

            for (Map<String, Object> obj : list) {
                formation f = new formation();

                float id = Float.parseFloat(obj.get("id").toString());
                f.setId((int) id);


                f.setTitre(obj.get("titre").toString());


             
                formations.add(f);
            }

        } catch (IOException ex) {
            System.out.println("Exception in parsing reclamations ");
        }

        return formations;
    }

    public ArrayList<formation> findAll() {
        String url = Statics.BASE_URL + "formation/List_Mobile";
        req.setUrl(url);
        req.setPost(false);
        req.addResponseListener(new ActionListener<NetworkEvent>() {
            @Override
            public void actionPerformed(NetworkEvent evt) {
                formations = parseformation(new String(req.getResponseData()));
                req.removeResponseListener(this);
            }
        });
        NetworkManager.getInstance().addToQueueAndWait(req);
        return formations;
    }

}
