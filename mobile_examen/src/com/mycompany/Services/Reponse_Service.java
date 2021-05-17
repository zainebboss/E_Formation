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
import com.mycompany.Models.Reponse;
import com.mycompany.Utils.Statics;
import java.io.IOException;
import java.util.ArrayList;
import java.util.List;
import java.util.Map;

/**
 *
 * @author user
 */
public class Reponse_Service  {
      public ArrayList<Reponse> Reponses;
    public static Reponse_Service instance = null;
    public boolean resultOK;
    private ConnectionRequest req;
     public Reponse_Service() {
        req = new ConnectionRequest();
    }

    public static Reponse_Service getInstance() {
        if (instance == null) {
            instance = new Reponse_Service();
        }
        return instance;
    }
        public ArrayList<Reponse> parseReponse(String jsonText) {
        try {
            Reponses = new ArrayList<>();
            JSONParser j = new JSONParser();
            Map<String, Object> ReclamationListJson = j.parseJSON(new CharArrayReader(jsonText.toCharArray()));

            List<Map<String, Object>> list = (List<Map<String, Object>>) ReclamationListJson.get("root");

            for (Map<String, Object> obj : list) {
                Reponse f = new Reponse();

                float id = Float.parseFloat(obj.get("id").toString());
                f.setId((int) id);


                f.setReponse(obj.get("reponse").toString());
          f.setVrai(obj.get("vrai").toString());

        
                Reponses.add(f);
            }

        } catch (IOException ex) {
            System.out.println("Exception in parsing reclamations ");
        }

        return Reponses;
    }

    public ArrayList<Reponse> findAll(int id) {
        String url = Statics.BASE_URL + "examen/passer_QCM_Reponses/"+id;
        req.setUrl(url);
        req.setPost(false);
        req.addResponseListener(new ActionListener<NetworkEvent>() {
            @Override
            public void actionPerformed(NetworkEvent evt) {
                Reponses = parseReponse(new String(req.getResponseData()));
                req.removeResponseListener(this);
            }
        });
        NetworkManager.getInstance().addToQueueAndWait(req);
        return Reponses;
    }

}
