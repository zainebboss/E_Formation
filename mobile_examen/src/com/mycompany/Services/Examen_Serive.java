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
import com.mycompany.Models.examen;
import com.mycompany.Utils.Statics;
import java.io.IOException;
import java.util.ArrayList;
import java.util.List;
import java.util.Map;

/**
 *
 * @author user
 */
public class Examen_Serive {
      public ArrayList<examen> examens;
    public static Examen_Serive instance = null;
    public boolean resultOK;
    private ConnectionRequest req;
     public Examen_Serive() {
        req = new ConnectionRequest();
    }

    public static Examen_Serive getInstance() {
        if (instance == null) {
            instance = new Examen_Serive();
        }
        return instance;
    }
        public ArrayList<examen> parseexamen(String jsonText) {
        try {
            examens = new ArrayList<>();
            JSONParser j = new JSONParser();
            Map<String, Object> ReclamationListJson = j.parseJSON(new CharArrayReader(jsonText.toCharArray()));

            List<Map<String, Object>> list = (List<Map<String, Object>>) ReclamationListJson.get("root");

            for (Map<String, Object> obj : list) {
                examen f = new examen();

                float id = Float.parseFloat(obj.get("id").toString());
                f.setId((int) id);
    float formationId = Float.parseFloat(obj.get("formationId").toString());
                f.setFormationId((int) formationId);

                f.setDescription(obj.get("description").toString());


             
                examens.add(f);
            }

        } catch (IOException ex) {
            System.out.println("Exception in parsing reclamations ");
        }

        return examens;
    }

    public ArrayList<examen> findAll(int id) {
        String url = Statics.BASE_URL + "examen/Exam_Mobile/"+id;
        req.setUrl(url);
        req.setPost(false);
        req.addResponseListener(new ActionListener<NetworkEvent>() {
            @Override
            public void actionPerformed(NetworkEvent evt) {
                examens = parseexamen(new String(req.getResponseData()));
                req.removeResponseListener(this);
            }
        });
        NetworkManager.getInstance().addToQueueAndWait(req);
        return examens;
    }

    
    
        public void sendmail(int id,int note) {
        String url = Statics.BASE_URL + "examen/note_mobile/"+id+"/"+note;
        req.setUrl(url);
        req.setPost(false);
        req.addResponseListener(new ActionListener<NetworkEvent>() {
            @Override
            public void actionPerformed(NetworkEvent evt) {
               
                req.removeResponseListener(this);
            }
        });
        NetworkManager.getInstance().addToQueueAndWait(req);
  
    }
    
    
}

